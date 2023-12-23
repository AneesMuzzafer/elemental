<?php

namespace Core\Main;

use Core\Request\Request;
use Core\Response\Response;
use Core\Router\Router;

class HTTPEngine
{
    private Router $router;

    public function __construct(private App $app)
    {
        $this->router = Router::getInstance();
    }

    public function run(Request $request)
    {
        // $this->router->registerRoutes();

        $controller = $this->getController($request);
        $response = new Response();

        if (is_callable($controller)) {

            $response->generate($controller());
        }

        return $response;
    }

    function getController(Request $request)
    {
        $method = $_SERVER["REQUEST_METHOD"];

        foreach ($this->router->routes[$method] as $route) {
            $path = array_key_exists("PATH_INFO", $_SERVER) ?  $_SERVER["PATH_INFO"] : $_SERVER["REQUEST_URI"];

            if ($path === $route->uri) {
                return $route->action;
            }
        }
    }
}
