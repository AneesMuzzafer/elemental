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
        $this->logAllRoutes();
    }

    private function logRoutes()
    {
        console_log(Helper::purpleText("Registered routes"));
        console_log("=================\n");

        $routes = $this->router->getRoutes();
        $r = 0;

        foreach ($routes as $method => $methodRoutes) {
            console_log(Helper::yellowText("$method") . ":\t Routes = " . Helper::greenText(count($methodRoutes)));
            $i = 1;

            foreach ($methodRoutes as $route) {
                console_log("\t $i) $route->method $route->uri");
                $i++;
                $r++;
            }
        }

        console_log(Helper::greenText("\nTotal Routes = $r"));
    }

    private function logAllRoutes(): void
    {
        $allRoutes = $this->router->getRoutes();

        $longestUriLength = 0;
        $routeList = [];

        foreach ($allRoutes as $method => $routes) {
            foreach ($routes as $r) {
                if ($longestUriLength < strlen($r->uri)) {
                    $longestUriLength = strlen($r->uri);
                }

                $routeList[] = [
                    "method" => $method,
                    "uri" => $r->uri,
                    "action" => is_array($r->action) ? basename($r->action[0]) . "@" . $r->action[1] : "(closure)",
                ];
            }
        }

        $longestUriLength += 4; // 4 space offset

        console_log(Helper::purpleText("Method\tURI" . str_repeat(" ", $longestUriLength - 3) . "Action"));
        console_log("======\t===" . str_repeat(" ", $longestUriLength - 3) . "======");

        foreach ($routeList as $r) {
            $m = $r['method'] == "GET" ? Helper::greenText($r['method']) : Helper::yellowText($r['method']);

            console_log(
                $m . "\t" . $r['uri'] .
                    str_repeat(" ", $longestUriLength - strlen($r['uri'])) . Helper::blueText($r['action'])
            );
        }
    }
}
