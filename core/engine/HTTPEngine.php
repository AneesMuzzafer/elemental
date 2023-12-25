<?php

namespace Core\Engine;

use Core\Main\App;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\ResponseGenerator;
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

        [$action, $resolvedArgs] = $this->router->resolveController($request);

        try {
            $response = $this->app->resolveMethod($action, $resolvedArgs);
        } catch (\Throwable $e) {
            $response = $this->app->make(Response::class);
        }

        return $this->prepareResponse($response, $request);
    }


    public function prepareResponse($response, $request)
    {
        $response = (new ResponseGenerator($response))->toResponse();
        return $response;
    }
}
