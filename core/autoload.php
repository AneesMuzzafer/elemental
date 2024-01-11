<?php

spl_autoload_register(function ($class) {

    $namespaceParts = explode("\\", $class);
    $className = array_pop($namespaceParts);
    $path = dirname(__DIR__) . "/" . implode("/", array_map('lcfirst', $namespaceParts)) . "/" . $className . ".php";

    if (file_exists($path)) {
        require_once $path;
    }
});
