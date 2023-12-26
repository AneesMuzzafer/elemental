<?php

use App\Controllers\TestController;
use App\Services\MailService;
use Core\Request\Request;
use Core\Router\Router;
use Core\View\View;

Router::get("/", function (MailService $mailService) {
    $msg = $mailService->send("From Route Callback");
    return "Rendered in /" . " -- " . $msg;
});

Router::get("/user/{id}/posts/{post_id:slug}", function (Request $request,  MailService $mailService, string $id, $postId, $nweid) {
    $msg = $mailService->send("From Route Callback");
    return $request;
    // return "From paramed route . " . $msg . "id is " . $id . " and post id is " . $postId . "--- "  ;
});

Router::get("/abc", function (Request $request) {
    return View::make("home", ["data" => "Allah-u-Akbar", "abc" => $request->data()["abc"]]);
});

Router::get("/abc/{x}/def/{y}/ghi/{z}", [TestController::class, "index"]);
