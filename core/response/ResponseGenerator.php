<?php

namespace Core\Response;

use Core\Main\App;
use Core\Resource\JSONResource;
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
            $response = App::getInstance()->make(Response::class);
        }


        if (is_scalar($this->content)) {
            $content = (string) $this->content;

            $response->setContent($content);
            $response->setHeader("Content-type", "text/html");
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

        if ($this->content instanceof JSONResource) {
            $response->setContent($this->content->toJson());
        }

        if ($this->content instanceof View) {
            $response->setContent($this->content->view());
        }

        return $response;
    }
}
