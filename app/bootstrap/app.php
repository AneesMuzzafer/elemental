<?php

use Core\Engine\ConsoleEngine;
use Core\Engine\WebEngine;
use Core\Interfaces\ConsoleEngineContract;
use Core\Interfaces\WebEngineContract;

$app = new Core\Main\Application();

require $app->basePath() . "/core/helper/global_loader.php";

$app->singleton(WebEngineContract::class, WebEngine::class);
$app->singleton(ConsoleEngineContract::class, ConsoleEngine::class);

return $app;
