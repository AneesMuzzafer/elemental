<?php

namespace Core\Console;

class Helper
{
    public function __construct()
    {
        $this->logAllCommands();
    }


    private function logAllCommands()
    {
        console_log("\nCommand \t\t\t\t Usage\n");
        console_log("php manifest start \t\t\t- Start the PHP server at default address & available port.");
        console_log("php manifest start --host=[] \t\t- Start the PHP server at specified host address.");
        console_log("php manifest start --port-[] \t\t- Start the PHP server at specified port.");
        console_log("php manifest start --host=[] --port-[] \t- Start the PHP server at specified host address & port.\n");
        console_log("php manifest build:model [name] \t- Build a Model class with the specified name.");
        console_log("php manifest build:controller [name] \t- Build a Controller class with the specified name.");
        console_log("php manifest build:middleware [name] \t- Build a Middlware class with the specified name.\n");
        console_log("php manifest route:list \t\t- List all the routes registered within the App.");
        console_log("php manifest help \t\t\t- List all the available commands.");
    }
}
