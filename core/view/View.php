<?php

namespace Core\View;

use Core\Exception\ViewNotFoundException;
use Core\Main\Application;

class View
{

    private string $path;
    private string $name;

    private string $layoutPath;
    private string $layoutName;

    private string $content = "";
    private string $layoutContent = "";


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

        $includeFile = function () {

            extract(func_get_arg(0));

            include func_get_arg(1);
        };

        $includeFile($this->params, $this->path);
        $this->content = ob_get_clean();

        unset($includeFile);
    }

    public function parseLayoutContent()
    {
        ob_start();

        $includeFile = function () {

            include func_get_arg(0);
        };

        $includeFile($this->layoutPath);
        $this->layoutContent = ob_get_clean();

        unset($includeFile);
    }

    public static function getPath($viewName)
    {
        $viewName = static::getCurrentPath($viewName);
        $path = Application::getInstance()->basePath() . "/app/views/" . $viewName . ".php";

        if (!file_exists($path)) {
            throw new ViewNotFoundException("Could not find " . $viewName . ".php");
        }
        return $path;
    }

    public static function getCurrentPath($viewName)
    {
        $segments = explode(".", $viewName);
        $path = implode("/", $segments);
        return $path;
    }

    public function view()
    {
        if (isset($this->layoutPath)) {

            $this->parseLayoutContent();
        }
        $this->compileContent();
        return $this->content;
    }

    public function withLayout($layoutName)
    {
        $this->layoutName = $layoutName;
        $layoutName = static::getCurrentPath($layoutName);

        $layoutPath = Application::getInstance()->basePath() . "/app/views/" . $layoutName . ".php";

        if (!file_exists($layoutPath)) {
            throw new ViewNotFoundException("Could not find " . $this->layoutName . ".php");
        }
        $this->layoutPath = $layoutPath;
        return $this;
    }

    public function compileContent()
    {
        if ($this->layoutContent == "") {
            return $this->content;
        }

        $this->content = str_replace('{{ content }}', $this->content, $this->layoutContent);
    }
}
