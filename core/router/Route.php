<?php

namespace Core\Router;

class Route
{

    protected Router $router;

    public String $method;
    public String $uri;
    public $action;

    public array $middleware = [];

    public array $routeSegments = [];

    public function __construct(String $method, String $uri, array | callable $action)
    {
        $this->router = Router::getInstance();
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;

        $this->generateRouteProperties();
    }

    public function generateRouteProperties(): void
    {
        $uri = $this->uri;

        if ($uri == "/") {

            $this->routeSegments = [
                ["key" => "/", "is_param" => false, "binding" => ""],
            ];
            return;
        }

        if ($uri[0] == "/") {
            $uri = substr($uri, 1);
        }

        $segments = explode('/', $uri);

        $this->routeSegments = array_map(function ($segment) {

            if ($segment === "") return false;

            $routeProp = [];
            if ($segment[0] === "{" && $segment[strlen($segment) - 1] === "}") {

                $segment = substr(substr($segment, 0, -1), 1);
                $parts = explode(":", $segment);

                $routeProp["key"] = $parts[0];
                $routeProp["is_param"] = true;
                if (count($parts) === 2) {
                    $routeProp["binding"] = $parts[1];
                } else {
                    $routeProp["binding"] = false;
                }
            } else {
                $routeProp["key"] = $segment;
                $routeProp["is_param"] = false;
                $routeProp["binding"] = false;
            }

            return $routeProp;
        }, $segments);
    }


    public static function get(String $uri, array | callable $action): static
    {
        $instance = new static("GET", $uri, $action);
        $instance->router->addRoute("GET", $instance);
        return $instance;
    }

    public function getMiddleware()
    {
        return $this->middleware;
    }

    public function middleware(array $middleware): static
    {
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }
}
