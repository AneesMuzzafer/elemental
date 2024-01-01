<?php

namespace Core\Response;

use Core\Main\Application;
use Core\Request\Request;

class Response
{

    protected Request $request;

    private string $statusCode = "200";

    public string $content = "";
    public array $headers = [];

    const STATUS_TEXT = [
        200 => "OK",
        201 => "Created",
        204 => "No Content",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
    ];

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
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function setStatusCode(string $statusCode): Response
    {
        $this->statusCode = $statusCode;
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

        header("HTTP/1.1 " . $this->statusCode . " " . self::STATUS_TEXT[$this->statusCode]);
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

    public static function redirect(string $url, int $status = 302)
    {
        Application::getInstance()->make(static::class)
            ->setHeader("Location", $url)
            ->setStatusCode($status)
            ->sendHeaders();

        exit(1);
    }

    public static function JSON(mixed $data = [], int $status = 200, array $headers = []): Response
    {
        return Application::getInstance()->make(static::class)
            ->setHeaders($headers)
            ->setStatusCode($status)
            ->setContent(json_encode($data));
    }
}
