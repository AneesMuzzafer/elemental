<?php

use Core\Router\Router;

function app()
{
    return $GLOBALS['app'];
}

function console_log(...$msgs)
{
    foreach ($msgs as $msg) {
        error_log(print_r($msg, true));
    }
}

function dump($instance)
{
    echo "<pre>";
    echo print_r($instance, true);
    echo "</pre>";
}

function router()
{
    return Router::getInstance();
}

function auth()
{
    return "auth";
}
