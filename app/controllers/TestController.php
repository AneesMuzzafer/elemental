<?php

namespace App\Controllers;

use App\Services\MailService;
use Core\Request\Request;

class TestController
{

    public function __construct()
    {
    }

    public function index(MailService $mailService, Request $request, String $x, $y, String $z)
    {
        $msg = $mailService->send("Message from Test Controller");
        return "Resolved from Index of Test Controller  - " . $msg . "with route params " . "$x, $y, $z" . " and request params are " . $request->data()["start"];
    }

    public function store(){
        return redirect("/");
    }

    public function fallback(){
        return "fallback";
    }
}
