<?php

namespace Core\Facade;

use Core\Main\App;

class Facade
{

    public static function __callStatic($name, $args)
    {
        $facadeAccessor = static::getFacadeAccessor();

        if ($facadeAccessor == "") return;

        return App::getInstance()->make($facadeAccessor)->$name(...$args);
    }

    protected static function getFacadeAccessor()
    {
        return "";
    }
}
