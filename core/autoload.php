<?php

spl_autoload_register(function ($class) {
    $path = dirname(__DIR__) . "/" . lcfirst(str_replace("\\", "/", $class) .  ".php");

    if (file_exists($path)) {
        require_once $path;
    }
});
