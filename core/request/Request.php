<?php

namespace Core\Request;

use SimpleXMLElement;
use stdClass;

class Request
{

    protected static $instance = null;

    public string $method;
    public string $uri;


    public array $headers = [];
    public mixed $rawContent;

    public array $cookies = [];

    public array $files = [];

    public array $data = [];

    public string $text;
    public string $js;
    public string $html;
    public stdClass $json;
    public SimpleXMLElement $xml;

    public string $contentType;

    public ?string $queryString;
    public ?string $remoteIP;
    public ?string $remotePort;


    public function __construct()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new Request();
        }
        return static::$instance;
    }

    public static function read()
    {
        $instance = static::getInstance();

        static::readServerAttributes($instance);

        static::readRequestAttributes($instance);

        static::readMethodAttributes($instance);

        static::parseBody($instance);

        static::readFiles($instance);

        dump($_SERVER, "server");
        dump($instance->headers, "headers");
        dump($instance->rawContent, "raw content");
        dump($_REQUEST, "request");
        dump($_GET, "GET");
        dump($_POST, "POST");
        dump($_FILES, "FILES");
        dump($instance, "instance");

        return $instance;
    }

    public static function readServerAttributes(Request $instance)
    {

        $instance->method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
        $instance->uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : "";

        $instance->headers = getallheaders();

        $instance->cookies = $_COOKIE;

        $instance->rawContent = file_get_contents("php://input");

        $instance->contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : "";

        $instance->queryString = isset($_SERVER["QUERY_STRING"]) ? $_SERVER["QUERY_STRING"] : "";
        $instance->remoteIP = isset($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_ADDR"] : "";
        $instance->remotePort = isset($_SERVER["REMOTE_PORT"]) ? $_SERVER["REMOTE_PORT"] : "";
    }

    public static function readRequestAttributes(Request $instance)
    {
        if (!is_array($_REQUEST)) return;

        foreach ($_REQUEST as $key => $value) {
            $instance->{$key} = $value;
            $instance->data[$key] = $value;
        }
    }

    public static function readMethodAttributes(Request $instance)
    {
        switch ($instance->method) {
            case "GET":
                static::readGetAttributes($instance);
                break;
            case "POST":
                static::readPostAttributes($instance);
                break;
            case "PUT":
            case "PATCH":
            case "'DELETE'":
            case "'HEAD":
            case "OPTIONS":
                static::readRawContent($instance);
                break;
            default:
                throw new \Exception("Request Method not defined");
        }
    }

    public static function readGetAttributes(Request $instance)
    {
        if (!is_array($_GET)) return;

        foreach ($_GET as $key => $value) {
            $instance->{$key} = $value;
            $instance->data[$key] = $value;
        }
    }

    public static function readPostAttributes(Request $instance)
    {
        if (!is_array($_POST)) return;

        foreach ($_POST as $key => $value) {
            $instance->{$key} = $value;
            $instance->data[$key] = $value;
        }
    }

    public static function readRawContent(Request $instance)
    {
    }

    public static function parseBody(Request $instance)
    {
        switch ($instance->contentType) {
            case "text/plain":
                $instance->text = $instance->rawContent;
                break;
            case "application/javascript":
                $instance->js = $instance->rawContent;
                break;
            case "text/html":
                $instance->html = $instance->rawContent;
                break;
            case "application/json":
                $instance->data = json_decode($instance->rawContent, true);
                $instance->json = json_decode($instance->rawContent);

                break;
            case "application/xml":
                $instance->xml = simplexml_load_string($instance->rawContent);
                $instance->data = json_decode(json_encode($instance->xml), true);
                break;
        }
    }

    public static function readFiles(Request $instance){
        $instance->files = $_FILES;
    }
}
