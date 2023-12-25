<?php

namespace Core\Main;

use ReflectionClass;

class Container
{
    protected array $coreInstances = [
        App::class,
        HttpEngine::class,
        ConsoleEngine::class,
        \Core\Router\Router::class,
        \Core\Request\Request::class,
        \Core\Response\Response::class
    ];

    protected array $coreBindings = [];

    protected array $instances = [];
    protected array $bindings = [];

    public function bind(String $key, String | callable $value)
    {
        if (!isset($this->bindings[$key])) {
            $this->bindings[$key] = $value;
        }
    }

    public function make(String $key)
    {
        if (in_array($key, $this->coreInstances)) {
            return $this->resolveInstance($key);
        }

        if (array_key_exists($key, $this->bindings)) {

            $value = $this->bindings[$key];

            if (is_callable($value)) {
                return call_user_func($value);
            } else {
                return $this->resolve($value);
            }
        }

        return $this->resolve($key);
    }

    public function resolveInstance(string $key)
    {
        return $key::getInstance();
    }

    public function resolve(String $key)
    {
        $reflection = new ReflectionClass($key);

        if (!$reflection->isInstantiable()) {
            throw new \Exception("Invalid class! " . $key . " is not instantiable.");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor != null) {
            $dependencies = $constructor->getParameters();
        }

        if ($constructor === null || count($dependencies) == 0) {
            return $reflection->newInstance();
        }

        $resolvedDependencies = array_map(function ($dependency) use ($key) {
            $name = $dependency->getName();
            $type = $dependency->getType();

            if ($type === null) {
                throw new \Exception("Failed to resolve class " . $key . ". " . $name . " is missing the type hint.");
            }

            if ($type->getName() === App::class) {
                return App::getInstance();
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

    public function resolveMethod(array|callable $action, array $args)
    {
        $i = 0;

        if (!is_array($action)) {
            $reflection = new \ReflectionFunction($action);
        } else {
            [$object, $method] = $action;
            $reflection = new \ReflectionMethod($object, $method);
        }

        $parameters = $reflection->getParameters();

        if (count($parameters) == 0) {
            return $reflection->invoke(...($object ? [$object] : []));
        }

        $resolvedParameters = array_map(function ($parameter) use ($args, &$i) {
            $type = $parameter->getType();

            if ($type instanceof \ReflectionUnionType) {
                throw new \Exception("Cannot resolve a union type");
            }

            if ($type == null || $type->getName() == "string") {
                return $i < count($args) ? $args[$i++]["value"] : null;
            }

            if ($type->isBuiltin()) {
                throw new \Exception("Param type cannot be " . $type->getName() . ".");
            }

            return $this->make($type->getName());
        }, $parameters);

        if ($reflection instanceof \ReflectionMethod) {
            return $reflection->invokeArgs($object, $resolvedParameters);
        }

        if ($reflection instanceof \ReflectionFunction) {
            return $reflection->invokeArgs($resolvedParameters);
        }

        return null;
    }
}
