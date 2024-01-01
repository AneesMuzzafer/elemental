<?php

namespace Core\Config;

use Core\Main\App;

class Config
{

    private array $config = [];

    private string $configPath;

    public function __construct()
    {
        $this->configPath = App::getInstance()->basePath() . "/app/config/config.php";

        if (!file_exists($this->configPath)) return;

        $this->config = require_once $this->configPath;
    }

    public function __get(string $name)
    {
        if (isset($this->config[$name])) {
            return $this->config[$name];
        }

        return null;
    }
}
