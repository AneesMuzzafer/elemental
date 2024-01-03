<?php

namespace Core\Console;

class Commander
{

    public $args = [];

    public function __construct(array $args)
    {
        $this->args = $args;
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
            return new Helper();
        }

        if (in_array($command, Builder::BUILD_COMMANDS)) {
            if (!isset($this->args[1])) {
                console_log("Please specify a name for the resource you want to build.");
                exit(1);
            }
            return new Builder($command, $this->args[1]);
        }

        console_log("Could not find any valid action for " . $command . " command.");
    }
}
