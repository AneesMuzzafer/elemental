<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;
use Core\Response\ResponseGenerator;

class AllowCORS
{
    public function handle(Request $request, Closure $next)
    {
        $response = (new ResponseGenerator($next($request)))->toResponse();

        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', '*');

        return $response;
    }
}
