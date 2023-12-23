<?php

namespace Core\Router;

class Route
{

    protected Router $router;

    public String $method;
    public String $uri;
    public $action;
    public Array $params = [];

    public function __construct(String $method, String $uri, Callable $action)
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->action = $action;
        $this->router = Router::getInstance();
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
