<?php

namespace Core\Engine;

use Core\Console\Commander;
use Core\Console\Input;
use Core\Interfaces\ConsoleEngineContract;
use Core\Main\Application;

class ConsoleEngine implements ConsoleEngineContract
{
    public function __construct(public Application $app)
    {
        $this->app->boot();
    }

    public function run(Input $input): void
    {
        $commander = $this->app->make(Commander::class)->setArgs($input->args);
        $commander->registerCommands();
        $commander->resolveCommand();
    }
}
