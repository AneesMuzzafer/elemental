<?php

spl_autoload_register(function ($class) {
    $path = __DIR__ . "/" . lcfirst(str_replace("\\", "/", $class) .  ".php");
    require_once $path;
});
