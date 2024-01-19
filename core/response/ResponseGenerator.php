<?php

namespace Core\Response;

use Core\Main\Application;
use Core\View\View;

class ResponseGenerator
{
    public function __construct(private $content, private int $status = 200, private ?string $contentType = null)
    {
        //
    }

    public function toResponse(): Response
    {
        if ($this->content instanceof Response) {
            $response = $this->content;
        } else {
            /** @var Response $response */
            $response = Application::getInstance()->make(Response::class);
            $response->setStatusCode($this->status);

            $content = $this->content;

            if (is_scalar($this->content)) {
                $content = (string) $this->content;

                $response->setHeader("Content-Type", $this->contentType ?? "text/plain");
            }

            if (is_array($this->content)) {
                $content = json_encode($this->content);

                $response->setHeader("Content-Type", $this->contentType ?? "application/json");
            }

            if (is_object($this->content)) {
                $content = json_encode(get_object_vars($this->content));

                $response->setHeader("Content-Type", $this->contentType ?? "application/json");
            }

            if ($this->content instanceof View) {
                $content = $this->content->view();

                $response->setHeader("Content-Type", $this->contentType ?? "text/html");
            }

            $response->setContent($content);
        }

        return $response;
    }
}
