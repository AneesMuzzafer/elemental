<?php

namespace Core\Console;

use Core\Main\Application;
use Core\Router\Router;

class Lister
{
    private Router $router;

    public function __construct()
    {
        $this->router = Application::getInstance()->make(Router::class);
        $this->logRoutes();
    }

    private function logRoutes()
    {
        console_log("Registered routes");
        console_log("=================\n");

        $routes = $this->router->getRoutes();
        $r = 0;

        foreach ($routes as $method => $methodRoutes) {
            console_log("$method:\t Routes = " . count($methodRoutes));
            $i = 1;

            foreach ($methodRoutes as $route) {
                console_log("\t $i) $route->method $route->uri");
                $i++;
                $r++;
            }
        }

        console_log("\nTotal Routes = $r");
    }
}
