<?php

use Core\Response\Response;
use Core\Router\Router;
use Core\View\View;

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
    return app()->make(Router::class);
}

function view($name, $params = [])
{
    return View::make($name, $params);
}

function component($name, $params = [])
{
    return View::make($name, $params)->view();
}

function redirect(string $url, int $code = 302)
{
    return Response::redirect($url, $code);
}
