<?php

namespace App\Middlewares;

use Core\Request\Request;

class HasAuth
{
    public function handle(Request $request, \Closure $next)
    {
        if ($request->data()["auth"] != "yes") {
            return ["code" => "unauthorized"];
        }

        return $next($request);
    }
}
