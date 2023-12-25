<?php

namespace App\Controllers;

use App\Services\MailService;
use Core\Request\Request;

class TestController
{

    public function __construct(MailService $mailService)
    {
    }

    public function index(MailService $mailService, Request $request, String $x, $y, String $z)
    {
        $msg = $mailService->send("Message from Test Controller");
        dump($_REQUEST, "request main");
        dump($request, "request");
        return "Resolved from Index of Test Controller  - " . $msg . "with route params " . "$x, $y, $z" . " and request params are " . $request->data()["start"];
    }
}
