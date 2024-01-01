<?php

namespace Core\Config;

use Core\Main\Application;

class EnvironmentLoader
{
    public function load(): void
    {
        $path = Application::getInstance()->basePath() . "/.env";

        if (!file_exists($path)) return;

        $lines = file($path);
        foreach ($lines as $line) {

            $parts = explode('=', $line, 2);
            if (count($parts) != 2) continue;

            $key = trim($parts[0]);
            $value = trim($parts[1]);

            if ($key === '') continue;

            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
