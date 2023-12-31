<?php

namespace Core\Facade;

use Core\Facade\Facade;
use Core\Router\Router;

class Route extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Router::class;
    }
}
