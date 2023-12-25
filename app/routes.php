<?php

use App\Controllers\TestController;
use App\Services\MailService;
use Core\Router\Router;

Router::get("/", function (MailService $mailService) {
    $msg = $mailService->send("From Route Callback");
    return "Rendered in /" . " -- " . $msg;
});

Router::get("/user/{id}/posts/{post_id:slug}", function (MailService $mailService, string $id, $postId, $nweid) {
    $msg = $mailService->send("From Route Callback");
    return "From paramed route . " . $msg . "id is " . $id . " and post id is " . $postId . "--- "  ;
});

Router::get("/abc", function (MailService $mailService, $id, $postId, $nweid) {
    return "in abc";
});

Router::get("/abc/{x}/def/{y}/ghi/{z}", [TestController::class, "index"]);
