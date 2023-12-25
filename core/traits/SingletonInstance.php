<?php

namespace Core\Traits;

use Core\Main\App;

trait SingletonInstance
{
    protected static $instance = null;

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            $app = App::getInstance();
            static::$instance = new static($app);
        }
        return static::$instance;
    }
}
