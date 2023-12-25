<?php

function app()
{
    return \Core\Main\App::getInstance();
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
    return \Core\Router\Router::getInstance();
}

function auth()
{
    return "auth";
}
