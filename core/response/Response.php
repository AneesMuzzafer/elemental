<?php

namespace Core\Response;

use Core\Request\Request;

class Response {

    protected Request $request;

    private string $status = 200;

    public string $content = "";

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function generate($data) {
        $this->content = $this->content . $data;
    }

    public function send() {
        echo $this->content;
    }
}
