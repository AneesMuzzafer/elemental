<?php

namespace Core\Router;

use Core\Exception\RouterException;

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

    private function __construct(){}

    public function addRoute(string $method, Route $route)
    {
        if (!isset($this->routes[$method])) {
            throw new RouterException("Invalid Route Method!");
        }
        $this->routes[$method][] = $route;
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function registerRoutes()
    {
        $path = str_replace("\\", "/", getcwd()) . "/app/routes.php";
        require_once $path;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public static function get(String $uri, callable $action): self
    {

        $route = new Route("GET", $uri, $action);

        $instance = self::$instance;

        $instance->addRoute("GET", $route);

        return $instance;
    }

    public static function post(String $uri, String | array $action = null): self
    {

        $route = new Route("POST", $uri, $action);

        $instance = self::$instance;

        $instance->addRoute("POST", $route);

        return $instance;
    }
}
