<?php

namespace Core\Main;

use App\Bootstrap\AppServiceProvider;
use Core\Config\EnvironmentLoader;
use Core\Router\Router;

class Application extends Container
{
    protected static ?Application $instance = null;

    protected ?Router $router;

    private $basePath;

    public function __construct()
    {
        if (self::$instance != null) {
            throw new \Core\Exception\AppException("App already initiated. Access the instance using App::getInstance() method");
        }

        $this->setBasePath();

        $this->resolvedInstances[Application::class] = $this;

        $this->initializeApp();
    }

    public function initializeApp()
    {
        self::$instance = $this;

        $this->router = $this->make(Router::class);

        $this->make(EnvironmentLoader::class)->load();
    }

    public function boot()
    {
        $this->router->registerRoutes();
        $appServiceProvider = $this->make(AppServiceProvider::class);
        $appServiceProvider->register();
        $appServiceProvider->boot();
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setBasePath()
    {
        if (basename(getcwd()) == "public") {
            $this->basePath = dirname(str_replace("\\", "/", getcwd()));
        } else {
            $this->basePath = str_replace("\\", "/", getcwd());
        }
    }

    public function basePath()
    {
        return $this->basePath;
    }

    public function terminate()
    {
        exit(1);
    }
}
