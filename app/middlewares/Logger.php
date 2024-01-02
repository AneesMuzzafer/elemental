<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;

class Logger
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->data()["log"] == "yes") {
            dump($request->uri());
        }
        return $next($request);
    }
}
