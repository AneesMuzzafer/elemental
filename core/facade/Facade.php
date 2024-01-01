<?php

namespace Core\Facade;

use Core\Main\Application;

class Facade
{

    public static function __callStatic($name, $args)
    {
        $facadeAccessor = static::getFacadeAccessor();

        if ($facadeAccessor == "") return;

        return Application::getInstance()->make($facadeAccessor)->$name(...$args);
    }

    protected static function getFacadeAccessor()
    {
        return "";
    }
}
