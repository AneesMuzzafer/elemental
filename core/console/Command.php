<?php

namespace Core\Console;

use Core\Main\Application;

class Command
{

    protected $key;
    protected $description;

    private Commander $commander;

    public function __construct()
    {
        $this->commander = Application::getInstance()->make(Commander::class);
        if (isset($this->key)) {
            $this->commander->addCommand($this->key, $this);
        }
    }

    public function getKey(){
        if(!isset($this->key)) return null;

        return $this->key;
    }

    public function getDescription() {
        if(!isset($this->description)) return null;

        return $this->description;
    }
}
