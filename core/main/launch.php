<?php

use Core\Main\App;

$app = new App();

require str_replace("\\", "/", getcwd()) . "/core/helper/global_loader.php";

// $app->bind(HTTPEngine::class, function () use ($app) {
//     return new HTTPEngine($app);
// });

// $app->bind(ConsoleEngine::class, function () use ($app) {
//     return new ConsoleEngine($app);
// });

return $app;
