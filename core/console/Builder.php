<?php

namespace Core\Console;

use Core\Main\Application;

class Builder
{
    private string $name = "";
    private bool $forceCreate = false;

    private array $controllerConfig = [
        'api' => false,
        'model' => null,
    ];

    const BUILD_MODEL = "build:model";
    const BUILD_CONTROLLER = "build:controller";
    const BUILD_MIDDLEWARE = "build:middleware";
    const BUILD_COMMAND = "build:command";

    const BUILD_COMMANDS = [self::BUILD_MODEL, self::BUILD_CONTROLLER, self::BUILD_MIDDLEWARE, self::BUILD_COMMAND];

    public function __construct(private string $resource, private array $args)
    {
        foreach ($args as $arg) {
            if ($arg == $resource) continue;

            if ($arg == "-f") {
                $this->forceCreate = true;
                continue;
            }

            if ($this->resource == self::BUILD_CONTROLLER) {
                if ($arg == "--api") {
                    $this->controllerConfig['api'] = true;
                }

                if (str_starts_with($arg, "--model=")) {
                    $this->controllerConfig['model'] = explode("=", $arg)[1];

                    if (!file_exists(Application::getInstance()->basePath() . "/app/models/" . $this->controllerConfig['model'] . ".php")) {
                        console_log(Helper::redText("Error: The specified model (") . Helper::yellowText($this->controllerConfig['model']) . Helper::redText(") does not exist."));
                        exit(1);
                    }
                }
            }

            if ($arg[0] != "-" && $this->name == "") {
                $this->name = $arg;
            }
        }

        if ($this->name == "") {
            console_log(Helper::redText("Error: Please specify a name for the resource you want to build."));
            exit(1);
        }

        $this->generateResource();
    }

    public function generateResource()
    {
        switch ($this->resource) {
            case self::BUILD_MODEL:
                return $this->generateModel();
            case self::BUILD_CONTROLLER:
                return $this->generateController();
            case self::BUILD_MIDDLEWARE:
                return $this->generateMiddleware();
            case self::BUILD_COMMAND:
                return $this->generateCommand();
        }
    }

    public function generateModel()
    {
        console_log("Generating Model named " . Helper::purpleText("$this->name"));

        $dir = Application::getInstance()->basePath() . "/app/models";
        $content = $this->getModelContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function generateController()
    {
        console_log("Generating Controller named " . Helper::purpleText("$this->name"));

        $dir = Application::getInstance()->basePath() . "/app/controllers";
        $content = $this->getControllerContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function generateMiddleware()
    {
        console_log("Generating Middleware named " . Helper::purpleText("$this->name"));

        $dir = Application::getInstance()->basePath() . "/app/middlewares";
        $content = $this->getMiddlewareContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function generateCommand()
    {
        console_log("Generating Command named " . Helper::purpleText("$this->name"));

        $dir = Application::getInstance()->basePath() . "/app/commands";
        $content = $this->getCommandContent();

        $this->createFile($dir, $this->name, $content);
    }

    public function createFile($dir, $filename, $content)
    {
        $filePath = $dir . '/' . $filename . ".php";

        if (file_exists($filePath) && !$this->forceCreate) {
            console_log(Helper::redText("Error: The file already exists. Aborting. Use ") . Helper::yellowText("-f") . Helper::redText(" to create anyway."));
            exit(1);
        }

        $file = fopen($filePath, 'w');

        if ($file) {
            fwrite($file, $content);
            fclose($file);

            console_log(Helper::greenText("File ") . Helper::purpleText("$filename.php") . Helper::greenText(" created successfully at ") . Helper::purpleText($dir));
        } else {
            console_log(Helper::redText("Error: Unable to create the file."));
            exit(1);
        }
    }

    private function getModelContent()
    {
        return "<?php

namespace App\Models;

use Core\Model\Model;

class $this->name extends Model
{
    //
}
";
    }

    private function getControllerContent()
    {
        if ($this->controllerConfig['api']) {
            $modelParameter = is_null($this->controllerConfig['model']) ? "int \$id" : $this->controllerConfig['model'] . " $" . lcfirst($this->controllerConfig['model']);

            return "<?php

namespace App\Controllers;

use Core\Request\Request;
" . (!is_null($this->controllerConfig['model']) ? "use App\Models\\" . $this->controllerConfig['model'] . ";\n" : "") . "
class $this->name
{
    public function index()
    {
        //
    }

    public function store(Request \$request)
    {
        //
    }

    public function show($modelParameter)
    {
        //
    }

    public function update(Request \$request, $modelParameter)
    {
        //
    }

    public function destroy($modelParameter)
    {
        //
    }
}
";
        } else {
            return "<?php

namespace App\Controllers;

class $this->name
{
    public function index()
    {
        //
    }
}
";
        }
    }

    private function getMiddlewareContent()
    {
        return "<?php

namespace App\Middlewares;

use Closure;
use Core\Request\Request;

class $this->name
{
    public function handle(Request \$request, Closure \$next)
    {
        return \$next(\$request);
    }
}
";
    }

    private function getCommandContent()
    {
        return "<?php

namespace App\Commands;

use Core\Console\Command;
use Core\Console\Commander;

class $this->name extends Command
{
    protected \$key = \"command_name\";

    public function handle(Commander \$commander)
    {
         // handle command here!
    }
}
";
    }
}
