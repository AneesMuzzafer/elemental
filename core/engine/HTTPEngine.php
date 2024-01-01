<?php

namespace Core\Engine;

use App\Exceptions\Handler;
use Core\Exception\ExceptionHandler;
use Core\Helper\Pipeline;
use Core\Main\App;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\ResponseGenerator;
use Core\Router\Router;
use Exception;

class HTTPEngine
{
    private Router $router;

    public function __construct(private App $app)
    {
        $this->router = $app->make(Router::class);
    }

    public function run(Request $request)
    {
        try {
            [$route, $args] = $this->router->resolveRoute($request);

            $response = (new Pipeline())
                ->makePipeline($route->getMiddleware())
                ->pass($request)
                ->atLastRun(function () use ($route, $args) {
                    return $this->process($route, $args);
                })->execute();
        } catch (\Throwable $e) {
            $response = $this->app->make(Handler::class)->handleException($e);
        }

        return $this->prepareResponse($response, $request);
    }

    public function process($route, $args)
    {
        [$action, $resolvedArgs] = $this->router->resolveController($route, $args);
        return $this->app->resolveMethod($action, $resolvedArgs);
    }


    public function prepareResponse($response, $request)
    {
        $response = (new ResponseGenerator($response))->toResponse();
        return $response;
    }
}
