<?php

namespace App\Middlewares;

use Core\Request\Request;

class HasToken
{
    public function handle($request, \Closure $next)
    {
        return $next($request);
        // return $next($request);
    }
}
