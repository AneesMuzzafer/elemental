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

        $controller = $this->router->resolveController($request);

        $response = new Response();

        if (is_callable($controller)) {

            $result = $this->app->resolveMethod($controller);
            $response->generate($result);
        }

        return $response;
    }
}
