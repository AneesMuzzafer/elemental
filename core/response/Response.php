<?php

namespace Core\Response;

use Core\Main\App;
use Core\Request\Request;

class Response
{

    protected Request $request;

    private string $statusCode = "200";
    private string $statusText = "OK";

    public string $content = "";
    public array $headers = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setContent(string $content): Response
    {
        $this->content = $content;
        return $this;
    }

    public function setHeader(string $name, string $value): Response
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setHeaders(array $headers): Response
    {
        $this->headers = [...$this->headers, $headers];
        return $this;
    }

    public function appendContent($data)
    {
        $this->content = $this->content . $data;
    }

    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->headers as $name => $value) {
            header($name . ": " . $value);
        }
    }

    public function sendContent()
    {
        echo $this->content;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    public static function redirect(string $url, int $code = 302): Response
    {
        $response = App::getInstance()->make(static::class);
        $response->setHeader("Location", $url);
        $response->statusCode = $code;
        $response->sendHeaders();
        exit(1);
    }
}
