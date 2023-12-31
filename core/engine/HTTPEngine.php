<?php

namespace Core\Engine;

use Core\Helper\Pipeline;
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
        $this->router = $app->make(Router::class);
    }

    public function run(Request $request)
    {

        foreach ($this->router->getRoutes()["GET"] as $route) {

            dump($route->uri, "uri");
            dump($route->middleware, "middleware");
            dump($route->prefix, "prefix");
            dump($route->name, "name");
        }

        [$route, $args] = $this->router->resolveRoute($request);

        $response = (new Pipeline())
            ->makePipeline($route->getMiddleware())
            ->pass($request)
            ->atLastRun(function () use ($route, $args) {
                return $this->process($route, $args);
            })->execute();

        return $this->prepareResponse($response, $request);
    }

    public function process($route, $args)
    {
        [$action, $resolvedArgs] = $this->router->resolveController($route, $args);
        try {
            $response = $this->app->resolveMethod($action, $resolvedArgs);
        } catch (\Throwable $e) {
            // $response = $this->app->make(Response::class);
            throw new \Exception(get_class($e) . " " . $e->getMessage());
        }
        return $response;
    }


    public function prepareResponse($response, $request)
    {
        $response = (new ResponseGenerator($response))->toResponse();
        return $response;
    }
}
