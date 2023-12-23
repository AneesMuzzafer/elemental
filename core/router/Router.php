<?php

namespace Core\Router;

use Core\Exception\RouterException;
use Core\Main\App;
use Core\Request\Request;

class Router
{
    public static ?self $instance = null;

    public array $routes = [
        "GET" => [],
        "POST" => [],
        "PATCH" => [],
        "PUT"   => [],
        "DEL" => [],
        "HEAD" => [],
    ];

    private function __construct()
    {
    }

    public function addRoute(string $method, Route $route)
    {
        if (!isset($this->routes[$method])) {
            throw new RouterException("Invalid Route Method!");
        }
        $this->routes[$method][] = $route;
    }

    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function registerRoutes()
    {
        $path = str_replace("\\", "/", getcwd()) . "/app/routes.php";
        require_once $path;
    }

    public function resolveController(Request $request)
    {
        $route = $this->resolveRoute($request);

        if ($route === null) {
            throw new RouterException("404! Route Not Found.");
        }


        if (is_callable($route->action)) {

            $action = $route->action;
        } else if (is_array($route->action)) {

            $controller = $route->action[0];
            $method = $route->action[1];

            $controllerInstance = App::getInstance()->make($controller);

            try {
                $action = [$controllerInstance, $method];
            } catch (\Throwable $e) {
                throw new RouterException("Could not call ". $method . " on " . $controller);
            }
        }

        if(!$action) {
            throw new RouterException("Could not resolve the Route controller");
        }

        return $action;
    }

    public function resolveRoute(Request $request): ?Route
    {
        $method = $_SERVER["REQUEST_METHOD"];

        $path = array_key_exists("PATH_INFO", $_SERVER) ?  $_SERVER["PATH_INFO"] : $_SERVER["REQUEST_URI"];

        foreach ($this->routes[$method] as $route) {

            if ($path === $route->uri) {
                return $route;
            }
        }

        return null;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public static function get(String $uri, array | callable $action): static
    {

        $route = new Route("GET", $uri, $action);

        $instance = self::$instance;

        $instance->addRoute("GET", $route);

        return $instance;
    }

    public static function post(String $uri, array | callable $action): static
    {

        $route = new Route("POST", $uri, $action);

        $instance = self::$instance;

        $instance->addRoute("POST", $route);

        return $instance;
    }
}
