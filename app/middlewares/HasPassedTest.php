<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;

class HasPassedTest {
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}