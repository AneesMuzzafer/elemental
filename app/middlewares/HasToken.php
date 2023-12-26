<?php

namespace App\Middlewares;

class HasToken
{
    public function handle($request, \Closure $next)
    {

        $request = $request . " processed in Has Token ";
        echo ":" . $request . ": as in Has Token. ";
        return $next($request);
        // return $next($request);
    }
}
