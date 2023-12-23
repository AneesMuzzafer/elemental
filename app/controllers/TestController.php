<?php

namespace App\Controllers;

use App\Services\MailService;

class TestController
{

    public function __construct(MailService $mailService)
    {
    }

    public function index(MailService $mailService)
    {
        $msg = $mailService->send("Message from Test Controller");
        return "Resolved from Index of Test Controller  - " . $msg;
    }
}
