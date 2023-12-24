<?php

use App\Controllers\TestController;
use App\Services\MailService;
use Core\Router\Router;

Router::get("/", function (MailService $mailService) {
    $msg = $mailService->send("From Route Callback");
    return "Rendered in /" . " -- " . $msg;
});

Router::get("/user/{id}/posts/{post_id:slug}", function () {
    return "from paramed route";
});

Router::get("/abc", [TestController::class, "index"]);
