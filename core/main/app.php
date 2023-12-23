<?php

namespace Core\Main;

use Core\Router\Router;
use ReflectionClass;

class App
{

    protected static ?App $instance = null;

    protected array $bindings = [];

    protected ?Router $router;

    public function __construct()
    {
        if (self::$instance != null) {
            throw new \Core\Exception\AppException("App already initiated. Access the instance using App::getInstance() method");
        }

        $this->initializeApp();
    }

    public function initializeApp()
    {
        self::$instance = $this;

        $this->router = Router::getInstance();

        $this->router->registerRoutes();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind($key, $value)
    {
        if (!isset($this->bindings[$key])) {
            $this->bindings[$key] = $value;
        }
    }

    public function make($key)
    {
        if (array_key_exists($key, $this->bindings)) {

            $resolver = $this->bindings[$key];

            if (is_callable($resolver)) {
                return $resolver();
            }
        }

        $reflection = new ReflectionClass($key);

        if (!$reflection->isInstantiable()) {
            throw new \Exception("Invalid class! " . $key . " is not instantiable.");
        }

        $constructor = $reflection->getConstructor();
        $dependencies = $constructor->getParameters();

        if ($constructor === null || count($dependencies) == 0) {
            return $reflection->newInstance();
        }

        $resolvedDependencies = array_map(function ($dependency) use ($key) {
            $name = $dependency->getName();
            $type = $dependency->getType();

            if ($type->getName() === App::class) {
                return self::getInstance();
            }

            if ($type === null) {
                throw new \Exception("Failed to resolve class " . $key . ". " . $name . " is missing the type hint.");
            }

            if ($type instanceof \ReflectionUnionType) {
                throw new \Exception("Failed to resolve " . $key . ". " . $name . " is a union type and can't be instantiated.");
            }

            if ($type->isBuiltin()) {
                throw new \Exception("Failed to resolve " . $key . ". " . $name . " is a built-in type and can't be instantiated.");
            }

            return $this->make($type->getName());

        }, $dependencies);

        return $reflection->newInstanceArgs($resolvedDependencies);
    }

    public function terminate()
    {
        exit(1);
    }
}
