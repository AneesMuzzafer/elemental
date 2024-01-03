<?php

namespace Core\Interfaces;

use Core\Console\Input;

interface ConsoleEngineContract
{
    public function run(Input $input): void;
}
