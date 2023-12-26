<?php

namespace App\Middlewares;

class HasAuth
{
    public function handle($request, \Closure $next)
    {
        $request = $request . "& processed in Auth Token";
        echo ":" . $request . ": as in Auth Token. ";
        $res = $next($request);
        $res = $res . " now processd in Auth Token again";
        return $res;
    }
}
