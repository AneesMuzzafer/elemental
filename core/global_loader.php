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

function dump($instance, $msg = "")
{
    echo "<span>$msg</span>";
    echo '<pre style="background-color: #f4f4f4; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">';
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
