<?php

use App\Controllers\TestController;
use App\Services\MailService;
use Core\Router\Router;

Router::get("/", function (MailService $mailService) {
    $msg = $mailService->send("From Route Callback");
    return "Rendered in /" . " -- " . $msg;
});

Router::get("/abc", [TestController::class, "index"]);
