<?php

namespace Core\Response;

class Response {

    public string $body = "";

    public function __construct() {

    }

    public function generate($data) {
        $this->body = $this->body . $data;
    }

    public function send() {
        echo $this->body;
    }
}
