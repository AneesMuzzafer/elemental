<?php

declare(strict_types=1);

use Core\Main\HTTPEngine;
use Core\Request\Request;

require "./autoload.php";

$app = require_once "./core/main/launch.php";

$engine = $app->make(HTTPEngine::class);

$request = Request::read();

// dump(router()->getRoutes());

$response = $engine->run($request);

$response->send();

$app->terminate();
