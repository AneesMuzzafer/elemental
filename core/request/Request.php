<?php

namespace Core\Request;

class Request
{

    protected static $instance = null;

    public function __construct()
    {
    }

    public static function getInstance(){
        if (is_null(static::$instance)) {
            static::$instance = new Request();
        }
        return static::$instance;
    }

    public static function read()
    {
        $instance = static::getInstance();
        return $instance;
    }
}
