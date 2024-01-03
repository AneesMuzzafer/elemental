<?php

namespace Core\Engine;

use App\Exceptions\Handler;
use Core\Helper\Pipeline;
use Core\Interfaces\WebEngineContract;
use Core\Main\Application;
use Core\Request\Request;
use Core\Response\Response;
use Core\Response\ResponseGenerator;
use Core\Router\Router;

class WebEngine implements WebEngineContract
{
    private Router $router;

    public function __construct(private Application $app)
    {
        $this->router = $app->make(Router::class);
        $this->app->boot();
    }

    public function run(Request $request): Response
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

        return $this->prepareResponse($response);
    }

    public function process($route, $args)
    {
        [$action, $resolvedArgs] = $this->router->resolveController($route, $args);
        return $this->app->resolveMethod($action, $resolvedArgs);
    }


    public function prepareResponse($response)
    {
        $response = (new ResponseGenerator($response))->toResponse();
        return $response;
    }
}
