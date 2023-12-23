<?php

namespace App\Services;

class MailService
{
    public function __construct()
    {
    }

    public function send($msg)
    {
        return "Sending Message: " . $msg . ".";
    }
}
