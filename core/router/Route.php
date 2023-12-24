<?php

namespace Core\Router;

class Route
{

    protected Router $router;

    public String $method;
    public String $uri;
    public $action;

    // public array $params = [];
    public array $segments = [];
    public array $routeSegments = [
        ["key" => "user", "is_param" => false, "bindingKey" => ""],
        ["key" => "abc", "is_param" => true, "bindingKey" => ""],
        ["key" => "posts", "is_param" => false, "bindingKey" => ""],
        ["key" => "post", "is_param" => true, "bindingKey" => "slug"],
    ];

    public function __construct(String $method, String $uri, array | callable $action)
    {
        $this->router = Router::getInstance();
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;

        $this->makeRouteProperties();
    }

    public function makeRouteProperties(): void
    {
        $uri = $this->uri;
        if ($this->uri != "/" && $this->uri[0] == "/") {
            $uri = substr($this->uri, 1);
        }

        $segments = explode('/', $uri);

        $segments_s = array_map(function ($segment) {

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
                    $routeProp["binding"] = "";
                }
            } else {
                $routeProp["key"] = $segment;
                $routeProp["is_param"] = false;
                $routeProp["binding"] = "";
            }

            return $routeProp;
        }, $segments);





        echo "<pre>";
        // var_dump($this->segments);
        var_dump($segments_s);
        echo "</pre>";
        // $this->params
    }

    // public static function get(String $uri, Callable  $action): self{

    //     $instance = new static();

    //     $instance->method = "GET";
    //     $instance->uri = $uri;
    //     $instance->action = $action;

    //     $instance->router->addRoute($instance->method, $instance);
    //     return $instance;
    // }

    // public static function post(String $uri, String | Array $action = null): self{

    //     $instance = new static();

    //     $instance->method = "POST";
    //     $instance->uri = $uri;
    //     $instance->action = $action;

    //     $instance->router->addRoute($instance->method, $instance);
    //     return $instance;
    // }

}
