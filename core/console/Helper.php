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
        console_log(self::purpleText("Candle - Elemental CLI"));

        console_log(self::yellowText("\nCommand \t\t\t\t Usage"));
        console_log("======= \t\t\t\t =====\n");

        console_log(self::greenText("php candle ignite") . " \t\t\t Start the PHP server at default address & available port.");
        console_log(self::greenText("php candle ignite --host=[]") . " \t\t Start the PHP server at specified host address.");
        console_log(self::greenText("php candle ignite --port-[]") . " \t\t Start the PHP server at specified port.");
        console_log(self::greenText("php candle ignite --host=[] --port-[]") . " \t Start the PHP server at specified host address & port.\n");

        console_log(self::greenText("php candle build:model [name]") . " \t\t Build a Model class with the specified name.");
        console_log(self::greenText("php candle build:controller [name]") . " \t Build a Controller class with the specified name.");
        console_log(self::greenText("php candle build:middleware [name]") . " \t Build a Middleware class with the specified name.\n");

        console_log(self::greenText("php candle route:list") . " \t\t\t List all the routes registered within the App.");
        console_log(self::greenText("php candle help") . " \t\t\t List all the available commands.\n");

        console_log(self::yellowText("Custom commands"));
        console_log("===============\n");

        foreach ($commands as $key => $command) {
            console_log(self::greenText("php candle $key") . " \t\t\t " . $command->getDescription());
        }
    }

    public static function greenText(string $message): string
    {
        return "\033[1;32m" . $message . "\033[0m";
    }

    public static function yellowText(string $message): string
    {
        return "\033[1;33m" . $message . "\033[0m";
    }

    public static function purpleText(string $message): string
    {
        return "\033[1;35m" . $message . "\033[0m";
    }
}
