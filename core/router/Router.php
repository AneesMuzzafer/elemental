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
        [$route, $args] = $this->resolveRoute($request);

        if ($route === null) {
            throw new RouterException("404! Route Not Found.");
        }

        if (is_callable($route->action)) {

            $action = $route->action;
        } else if (is_array($route->action)) {

            [$controller, $method] = $route->action;

            $controllerInstance = App::getInstance()->make($controller);

            $action = [$controllerInstance, $method];
        }

        if (!$action) {
            throw new RouterException("Could not resolve the Route controller");
        }

        if (!is_callable($action)) {
            throw new RouterException("Route controller not callable!");
        }

        return [$action, $args];
    }

    public function resolveRoute(Request $request)
    {

        $method = $request->method();
        $requestURI = $request->uri();

        $path = ($pos = strpos($requestURI, "?")) !== false ? substr($requestURI, 0, $pos) : $requestURI;

        if ($path == "/") {
            $segments = ["/"];
        } else {

            if ($path[0] == "/") {
                $path = substr($path, 1);
            }

            $segments = explode('/', $path);
        }


        foreach ($this->routes[$method] as $route) {

            if (count($route->routeSegments) !== count($segments)) continue;

            $flag = true;
            $args = [];
            for ($i = 0; $i < count($segments); $i++) {
                $uriSegment = $route->routeSegments[$i];
                $pathSegment = $segments[$i];

                if ($uriSegment["is_param"]) {
                    $args[] = ["key" => $uriSegment["key"], "value" => $pathSegment, "binding" => $uriSegment["binding"]];
                    continue;
                }

                if ($uriSegment["key"] != $pathSegment) {
                    $flag = false;
                };
            }

            if ($flag) {
                return [$route, $args];
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
