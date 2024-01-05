<?php

namespace App\Commands;

use Core\Console\Command;
use Core\Console\Commander;

class Migration extends Command
{
    protected $key = "migrate";
    protected $description = "Custom migration handler.";

    public function __construct(private Commander $commander)
    {
        parent::__construct();
    }

    public function handle()
    {
        //handle command here!
    }
}
