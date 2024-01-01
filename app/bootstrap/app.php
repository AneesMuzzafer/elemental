<?php

$app = new \Core\Main\App();

require $app->basePath() . "/core/helper/global_loader.php";

// $app->bind(HTTPEngine::class, function () use ($app) {
//     return new HTTPEngine($app);
// });

// $app->bind(ConsoleEngine::class, function () use ($app) {
//     return new ConsoleEngine($app);
// });

return $app;
