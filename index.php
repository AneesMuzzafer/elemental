<?php

use Core\Engine\HTTPEngine;
use Core\Request\Request;

require "./autoload.php";

$app = require_once "./core/main/launch.php";

$engine = $app->make(HTTPEngine::class);

$request = Request::read();

$response = $engine->run($request);

$response->send();

$app->terminate();
