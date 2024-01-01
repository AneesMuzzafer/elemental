<?php

namespace Core\Response;

use Core\Main\Application;
use Core\View\View;

class ResponseGenerator
{
    public function __construct(private $content)
    {
    }

    public function toResponse(): Response
    {
        if ($this->content instanceof Response) {
            $response = $this->content;
        } else {
            $response = Application::getInstance()->make(Response::class);
            if (is_scalar($this->content)) {
                $content = (string) $this->content;

                $response->setContent($content);
                $response->setHeader("Content-type", "text/plain");
            }

            if (is_array($this->content)) {
                $content = json_encode($this->content);
                $response->setContent($content);
                $response->setHeader("Content-type", "application/json");
            }

            if (is_object($this->content)) {
                $content = json_encode(get_object_vars($this->content));
                $response->setContent($content);
                $response->setHeader("Content-type", "application/json");
            }

            if ($this->content instanceof View) {
                $response->setContent($this->content->view());
                $response->setHeader("Content-type", "text/html");
            }
        }

        return $response;
    }
}
