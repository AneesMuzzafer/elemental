<?php

namespace Core\Engine;

use Core\Console\Commander;
use Core\Console\Input;
use Core\Main\App;

class ConsoleEngine
{

    public function __construct(public App $app)
    {
        $this->app->boot();
    }

    public function run(Input $input)
    {
        $commander = new Commander($input->args);
        $commander->resolveCommand();
    }
}
