<?php

namespace Core\Engine;

use Core\Console\Commander;
use Core\Console\Input;
use Core\Main\App;

class ConsoleEngine
{

    public function __construct(public App $app)
    {
    }

    public function run(Input $input): void
    {
        $res = (new Commander($input->args))->resolveCommand();
    }
}
