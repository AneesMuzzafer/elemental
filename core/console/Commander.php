<?php

namespace Core\Console;

class Commander
{

    public $args = [];
    const CREATION_COMMANDS = ["create:model", "create:controller", "create:middleware"];

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function resolveCommand()
    {
        if(!isset($this->args[0])) {
            console_log("Missing the command. Use `php manifest help` to see the list of valid commands.");
            exit(1);
        }

        $command = $this->args[0];
        if ($command === "start") {
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

        if ($command === "diver") {
            return new Diver();
        }

        if ($command === "help") {
            return new Helper();
        }

        if (in_array($command, self::CREATION_COMMANDS)) {
            return new Builder($command, $this->args[1]);
        }

        console_log("Could not find any valid action for " . $command . " command.");
    }
}
