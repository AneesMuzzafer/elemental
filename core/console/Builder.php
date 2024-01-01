<?php

namespace Core\Console;

use Core\Main\Application;

class Builder
{
    private string $resource;
    private string $name;

    const BUILD_COMMANDS = ["build:model", "build:controller", "build:middleware"];

    public function __construct(string $resource, string $name)
    {
        $this->resource = $resource;
        $this->name = $name;
        $this->generateResource();
    }

    public function generateResource()
    {
        switch ($this->resource) {
            case self::BUILD_COMMANDS[0]:
                return $this->generateModel();
            case self::BUILD_COMMANDS[1]:
                return $this->generateController();
            case self::BUILD_COMMANDS[2]:
                return $this->generateMiddleware();
        }
    }

    public function generateModel()
    {
        console_log("Generating Model named $this->name.");
        $dir = Application::getInstance()->basePath() . "/app/models";
        $content = $this->getModelContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function generateController()
    {
        console_log("Generating Controller named $this->name.");
        $dir = Application::getInstance()->basePath() . "/app/controllers";

        $content = $this->getControllerContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function generateMiddleware()
    {
        console_log("Generating Middlware named $this->name.");
        $dir = Application::getInstance()->basePath() . "/app/middlewares";

        $content = $this->getMiddlewareContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function createFile($dir, $filename, $content)
    {
        $filePath = $dir . '/' . $filename . ".php";

        $file = fopen($filePath, 'w');

        if ($file) {
            fwrite($file, $content);
            fclose($file);

            console_log("File '$filename.php' created successfully at $dir");
        } else {
            console_log("Unable to create the file.");
            exit(1);
        }
    }

    private function getModelContent()
    {
        return "<?php

namespace App\Models;
use Core\Model\Model;

class $this->name extends Model {

}";
    }

    private function getControllerContent()
    {
        return "<?php

namespace App\Controllers;

class $this->name {

    public function index()
    {

    }
}";
    }

    private function getMiddlewareContent()
    {
        return "<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;

class $this->name {
    public function handle(Request \$request, Closure \$next)
    {
        return \$next(\$request);
    }
}";
    }
}
