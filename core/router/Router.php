<?php

namespace Core\Router;

use Core\Exception\RouteNotFoundException;
use Core\Exception\RouterException;
use Core\Main\Application;
use Core\Request\Request;

class Router
{
    public array $routes = [
        "GET" => [],
        "POST" => [],
        "PATCH" => [],
        "PUT"   => [],
        "DEL" => [],
        "HEAD" => [],
    ];

    public $fallback;

    public array $attributes = [
        "middleware" => [],
        "prefix" => "",
        "name" => "",
    ];

    public function __construct()
    {
    }

    public function addRoute(string $method, Route $route)
    {
        if (!isset($this->routes[$method])) {
            throw new RouterException("Invalid Route Method!");
        }

        if (isset($this->attributes["middleware"])) {
            $route->middleware = $this->attributes["middleware"];
        }

        $this->routes[$method][] = $route;
    }

    public static function getInstance(): self
    {
        return Application::getInstance()->make(static::class);
    }

    public function registerRoutes()
    {
        $path = Application::getInstance()->basePath() . "/app/routes.php";
        require_once $path;
    }

    public function resolveController(Route $route, array $args)
    {
        if (is_callable($route->action)) {

            $action = $route->action;
        } else if (is_array($route->action)) {

            [$controller, $method] = $route->action;

            $controllerInstance = Application::getInstance()->make($controller);

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

        if (isset($this->fallback)) {
            return [$this->fallback, []];
        }

        throw new RouteNotFoundException("404! The route with uri: '$requestURI' could not be found.");
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    private function generateRoute(string $method, String $uri, array | callable $action): Route
    {
        $route = new Route($method, $uri, $action);
        $this->addRoute($method, $route);
        return $route;
    }

    public function get(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("GET", $uri, $action);
    }

    public function post(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("POST", $uri, $action);
    }

    public function put(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("PUT", $uri, $action);
    }

    public function patch(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("PATCH", $uri, $action);
    }

    public function delete(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("'DELETE'", $uri, $action);
    }

    public function head(string $uri, array | callable $action): Route
    {
        return $this->generateRoute("HEAD", $uri, $action);
    }

    public function fallback(array | callable $action)
    {
        $fallback = new Route("FALLBACK", "*", $action);
        $this->fallback = $fallback;
        return $fallback;
    }

    public function group(array $attributes, callable $callback)
    {
        $prevAttributes =  $this->getAttributes();

        $this->setAttributes(array_merge_recursive($prevAttributes, $attributes));

        $callback();

        $this->setAttributes($prevAttributes);
    }
}
