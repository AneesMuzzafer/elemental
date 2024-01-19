<?php

namespace Core\Console;

class Helper
{
    public function __construct($commands)
    {
        $this->logAllCommands($commands);
    }

    private function logAllCommands($commands): void
    {
        console_log("\nCommand \t\t\t\t Usage");
        console_log("========= \t\t\t\t =====\n");

        console_log("php candle ignite \t\t\t Start the PHP server at default address & available port.");
        console_log("php candle ignite --host=[] \t\t Start the PHP server at specified host address.");
        console_log("php candle ignite --port-[] \t\t Start the PHP server at specified port.");
        console_log("php candle ignite --host=[] --port-[] \t Start the PHP server at specified host address & port.\n");

        console_log("php candle build:model [name] \t\t Build a Model class with the specified name.");
        console_log("php candle build:controller [name] \t Build a Controller class with the specified name.");
        console_log("php candle build:middleware [name] \t Build a Middleware class with the specified name.\n");

        console_log("php candle route:list \t\t\t List all the routes registered within the App.");
        console_log("php candle help \t\t\t List all the available commands.\n");

        console_log("Custom commands");
        console_log("===============\n");

        foreach ($commands as $key => $command) {
            $desc = $command->getDescription();
            console_log("php candle $key \t\t\t $desc");
        }
    }
}
