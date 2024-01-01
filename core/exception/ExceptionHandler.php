<?php

namespace Core\Exception;

use Core\Response\Response;
use Exception;

class ExceptionHandler
{
    public function __construct(private Response $response)
    {
    }

    public function handleException($e)
    {
        $response = $this->response->setContent($this->formatErrorMessage($e, "Exception!"));

        if ($e instanceof RouteNotFoundException) {
            $response = $this->response->setContent($this->formatErrorMessage($e, "Route Not Found Exception!"));
        }
        if ($e instanceof ModelNotFoundException) {
            $response = $this->response->setContent($this->formatErrorMessage($e, "Model Not Found Exception!"));
        }
        if ($e instanceof RouterException) {
            $response = $this->response->setContent($this->formatErrorMessage($e, "Router Exception!"));
        }
        if ($e instanceof ViewNotFoundException) {
            $response = $this->response->setContent($this->formatErrorMessage($e, "View Not Found Exception!"));
        }
        if ($e instanceof AppException) {
            $response = $this->response->setContent($this->formatErrorMessage($e, "App Exception!"));
        }


        $handler = $this->handle($e);
        if (!is_null($handler)) {
            $response = $handler;
        }

        return $response;
    }

    public function handle(Exception $e)
    {
        return null;
    }

    public function formatErrorMessage(Exception $e, $title)
    {
        $message = $e->getMessage();
        $class = get_class($e);

        return "
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f8f9fa;
                    color: #495057;
                    margin: 0;
                    padding: 20px;
                }

                .error-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    border: 1px solid #e0e0e0;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                }

                h1 {
                    color: #d9534f;
                }

                h2 {
                    font-size: 1.5em;
                    margin-bottom: 10px;
                }

                h3 {
                    font-size: 1.2em;
                    margin-top: 10px;
                }

                h4 {
                    font-size: 1em;
                    margin-top: 5px;
                    color: #333;
                }

                pre {
                    white-space: pre-wrap;
                    word-wrap: break-word;
                    margin-top: 15px;
                    background-color: #f8f9fa;
                    padding: 10px;
                    border: 1px solid #e0e0e0;
                    border-radius: 5px;
                    overflow: auto;
                }
            </style>

            <div class='error-container'>
                <h1>$title</h1>
                <h2>$message</h2>
                <h3>Class: $class</h3>
                <pre>$e</pre>
            </div>
        ";
    }
}
