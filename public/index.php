<?php

use Core\Engine\WebEngine;
use Core\Request\Request;

require __DIR__ . "/../core/autoload.php";

$app = require_once __DIR__ . "/../app/bootstrap/app.php";

$engine = $app->make(WebEngine::class);

$request = Request::read();

$response = $engine->run($request);

$response->send();

$app->terminate();