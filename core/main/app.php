<?php

namespace Core\Main;

use Core\Config\EnvironmentLoader;
use Core\Router\Router;
use ReflectionClass;

class App extends Container
{
    protected static ?App $instance = null;

    protected ?Router $router;

    public function __construct()
    {
        if (self::$instance != null) {
            throw new \Core\Exception\AppException("App already initiated. Access the instance using App::getInstance() method");
        }

        $this->resolvedInstances[App::class] = $this;

        $this->initializeApp();
    }

    public function initializeApp()
    {
        self::$instance = $this;

        $this->router = $this->make(Router::class);

        $this->router->registerRoutes();

        $this->make(EnvironmentLoader::class)->load();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function terminate()
    {
        exit(1);
    }
}
