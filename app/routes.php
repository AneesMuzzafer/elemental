<?php

use App\Controllers\TestController;
use Core\Router\Router;

Router::get("/", function () {
    return "Rendered in /";
});

Router::get("/abc", [TestController::class, "index"]);
