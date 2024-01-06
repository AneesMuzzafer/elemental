<?php

namespace Core\Request;

use Core\Main\Application;
use SimpleXMLElement;
use stdClass;

/**
 * Class Request
 *
 * @property mixed $dynamicProperty
 */
class Request
{
    protected Application $app;

    protected string $method;

    protected string $uri;
    protected string $uriDecoded;

    protected array $headers = [];
    protected mixed $rawContent;

    protected array $cookies = [];

    protected array $data = [];

    protected array $files = [];

    protected string $text;
    protected string $js;
    protected string $html;
    protected stdClass $json;
    protected SimpleXMLElement $xml;

    protected string $contentType;

    protected ?string $queryString;
    protected ?string $remoteIP;
    protected ?string $remotePort;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public static function read()
    {
        $instance = app()->make(Request::class);

        $instance->readServerAttributes();

        $instance->readRequestAttributes();

        $instance->readMethodAttributes();

        $instance->parseBody();

        $instance->readFiles();

        return $instance;
    }

    public function readServerAttributes()
    {

        $this->method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
        $this->uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";
        $this->uriDecoded = urldecode($this->uri);

        $this->headers = getallheaders();

        $this->cookies = $_COOKIE;

        $this->rawContent = file_get_contents("php://input");

        $this->contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : "";

        $this->queryString = isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : "";
        $this->remoteIP = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
        $this->remotePort = isset($_SERVER["REMOTE_PORT"]) ? $_SERVER["REMOTE_PORT"] : "";
    }

    public function readRequestAttributes()
    {
        if (!is_array($_REQUEST)) return;

        foreach ($_REQUEST as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function readMethodAttributes()
    {
        switch ($this->method) {
            case "GET":
                $this->readGetAttributes();
                break;
            case "POST":
                $this->readPostAttributes();
                break;
            case "PUT":
            case "PATCH":
            case "'DELETE'":
            case "'HEAD":
            case "OPTIONS":
                $this->readRawContent();
                break;
            default:
                throw new \Exception("Request Method not defined");
        }
    }

    public function readGetAttributes()
    {
        if (!is_array($_GET)) return;

        foreach ($_GET as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function readPostAttributes()
    {
        if (!is_array($_POST)) return;

        foreach ($_POST as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    public function readRawContent()
    {
    }

    public function parseBody()
    {
        switch ($this->contentType) {
            case "text/plain":
                $this->text = $this->rawContent;
                break;
            case "application/javascript":
                $this->js = $this->rawContent;
                break;
            case "text/html":
                $this->html = $this->rawContent;
                break;
            case "application/json":
                $this->data = json_decode($this->rawContent, true);
                $this->json = json_decode($this->rawContent);
                break;
            case "application/xml":
                $this->xml = simplexml_load_string($this->rawContent);
                $this->data = json_decode(json_encode($this->xml), true);
                break;
        }
    }

    public function readFiles()
    {
        $this->files = $_FILES;
    }

    // Getters

    public function method()
    {
        return $this->method;
    }

    public function uri()
    {
        return $this->uriDecoded;
    }

    public function headers()
    {
        return $this->headers;
    }

    public function rawContent()
    {
        return $this->rawContent;
    }

    public function cookies()
    {
        return $this->cookies;
    }

    public function data()
    {
        return $this->data;
    }

    public function files()
    {
        return $this->files;
    }

    public function text()
    {
        return $this->text;
    }

    public function js()
    {
        return $this->js;
    }

    public function html()
    {
        return $this->html;
    }

    public function json()
    {
        return $this->json;
    }

    public function xml()
    {
        return $this->xml;
    }

    public function contentType()
    {
        return $this->contentType;
    }

    public function queryString()
    {
        return $this->queryString;
    }

    public function ip()
    {
        return $this->remoteIP;
    }

    public function port()
    {
        return $this->remotePort;
    }

    public function __get($name)
    {
        if (!isset($this->data[$name])) {
            return null;
        }

        return $this->data[$name];
    }
}
