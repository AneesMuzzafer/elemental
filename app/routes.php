<?php

use App\Controllers\TestController;
use App\Middlewares\HasAuth;
use App\Middlewares\HasToken;
use App\Models\Post;
use App\Services\MailService;
use Core\Config\Config;

use Core\Helper\Pipeline;
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

    $result = (new Pipeline())
        ->makePipeline([HasToken::class, HasAuth::class])
        ->pass("req")
        ->atLastRun(function ($request) {
            return $request . "processed in then Closure .";
        })->execute();

    dump($result, "abc");


    return "end";
    // return view("home", ["data" => "Allah-u-Akbar", "abc" => "def"])->withLayout("layout.layout");
});

Router::get("/abc/{x}/def/{y}/ghi/{z}", [TestController::class, "index"]);

Router::get("/db/{post:title}", function (Request $request, Post $post, Config $config) {

    // $post = Post::create([
    //     // "name" => "Anees"
    //     "title" => "Test Title",
    //     "url" => "Test URL 2",
    //     "excerpt" => "Test Excerpt",
    //     "body" => "Test body",
    //     "user_id" => 2,
    //     "category_id" => 10,
    //     "published_at" => date("Y-m-d H:i:s"),
    // ]);

    // $post = Post::where(["title" => "Daanya"]);

    // $post->title = "John Doe";
    // $post->url = "New URL updated";
    // $post->excerpt = "New Excerpt";
    // $post->body = "New Body";
    // $post->user_id = "2";
    // $post->category_id = "13";

    // $post->destroy();

    return [
        "status" => "success",
        "post" => $post->data(),
        "request" => $request->data()
    ];
});
