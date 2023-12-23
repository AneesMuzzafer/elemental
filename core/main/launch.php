<?php

use Core\Main\App;
use Core\Main\ConsoleEngine;
use Core\Main\HTTPEngine;

$app = new App();

require "./core/global_loader.php";

// $app->bind(HTTPEngine::class, function () use ($app) {
//     return new HTTPEngine($app);
// });

// $app->bind(ConsoleEngine::class, function () use ($app) {
//     return new ConsoleEngine($app);
// });

return $app;
