<?php

namespace App\Commands;

use Core\Console\Command;
use Core\Console\Commander;

class Migration extends Command
{
    protected $key = "migrate";
    protected $description = "Custom migration handler.";

    public function handle()
    {
        //handle command here!
    }
}
