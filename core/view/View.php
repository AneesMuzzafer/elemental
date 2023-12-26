<?php

namespace Core\View;

use Core\Exception\ViewNotFoundException;

class View
{

    private string $path;
    private string $name;
    private string $content;
    private array $params;

    public function __construct(string $name, array $params = [])
    {
        $this->name = $name;
        $this->params = $params;

        $this->path = static::getPath($this->name);
        $this->parseContent();
    }

    public static function make(string $name, array $params = [])
    {
        return new static($name, $params);
    }

    public function parseContent()
    {
        ob_start();

        extract($this->params);

        include $this->path;

        $this->content =  ob_get_clean();

    }

    public static function getPath($viewName)
    {
        $path = str_replace("\\", "/", getcwd()) . "/app/views/" . $viewName . ".php";

        if (!file_exists($path)) {
            throw new ViewNotFoundException("Could not find " . $viewName . ".php");
        }
        return $path;
    }

    public function view()
    {
        return $this->content;
    }
}
