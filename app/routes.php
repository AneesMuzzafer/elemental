<?php

use App\Controllers\TestController;
use App\Services\MailService;
use Core\Router\Router;

Router::get("/", function (MailService $mailService) {
    $msg = $mailService->send("From Route Callback");
    return "Rendered in /" . " -- " . $msg;
});

Router::get("/user/{id}/posts/{post_id:slug}", function (MailService $mailService, $id, $postId, $nweid) {
    $msg = $mailService->send("From Route Callback");
    return "From paramed route . " . $msg . "id is " . $id . " and post id is " . $postId . "--- " . $nweid ;
});

Router::get("/abc", [TestController::class, "index"]);
