<?php

namespace Core\Console;

use Core\Main\Application;

class Commander
{

    public $args = [];

    private array $commands = [];

    public function __construct()
    {
    }

    public function resolveCommand()
    {
        if (!isset($this->args[0])) {
            console_log("Missing the command argument. Use `php candle help` to see the list of valid commands.");
            exit(1);
        }

        $command = $this->args[0];
        if ($command === "ignite") {
            if (isset($this->args[1])) {
                $arg1 = $this->args[1];
            }

            if (isset($this->args[2])) {
                $arg2 = $this->args[2];
            }

            return new Server($arg1 ?? null, $arg2 ?? null);
        }

        if ($command === "route:list") {
            return new Lister();
        }

        if ($command === "help") {
            return new Helper($this->commands);
        }

        if (in_array($command, Builder::BUILD_COMMANDS)) {
            if (!isset($this->args[1])) {
                console_log("Please specify a name for the resource you want to build.");
                exit(1);
            }
            return new Builder($command, $this->args[1]);
        }

        if (array_key_exists($command, $this->commands)) {
            $instance = $this->commands[$command];

            if (method_exists($instance, "handle")) {
                $action = [$instance, "handle"];
                return app()->resolveMethod($action, []);
            }
        }

        console_log("Could not find any valid action for " . $command . " command.");
    }

    public function setArgs($args)
    {
        $this->args = $args;
        return $this;
    }

    public function getArgs() {
        return $this->args;
    }

    public function registerCommands()
    {
        $app =  Application::getInstance();
        $commands = require $app->basePath() . "/app/commands/Commands.php";

        foreach ($commands as $command) {
            $app->make($command);
        }
    }

    public function addCommand($key, $instance)
    {
        $this->commands[$key] = $instance;
    }

    public function getInstance($key)
    {
        return $this->commands[$key];
    }
}
