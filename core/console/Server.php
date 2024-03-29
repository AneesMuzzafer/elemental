<?php

namespace Core\Console;

class Server
{
    private string $host = "127.0.0.1";
    private int $port = 8000;

    public function __construct($arg1 = null, $arg2 = null)
    {
        $this->port = $this->getAvailablePort();

        if (!is_null($arg1)) {
            $this->parseArg($arg1);
        }

        if (!is_null($arg2)) {
            $this->parseArg($arg2);
        }

        console_log(Helper::purpleText("Elemental: starting server on http://$this->host:$this->port"));
        console_log(Helper::yellowText("Press Ctrl+C to stop the server."));

        $command = "php -S $this->host:$this->port -t public";
        shell_exec($command);
    }

    private function getAvailablePort(): int
    {
        $port = $this->port;

        while ($this->isPortAvailable($port) == false) {
            $port = $port + 1;
        }

        return $port;
    }

    function isPortAvailable($port)
    {
        $socket = @fsockopen('127.0.0.1', $port, $errno, $errstr, 1);

        if ($socket === false) {
            return true;
        }

        fclose($socket);

        return false;
    }

    private function parseArg($arg)
    {
        $parts = explode("=", $arg);
        if (count($parts) != 2) return;

        if ($parts[0] == "--host" && $this->isValidHost(trim($parts[1]))) {
            $this->host = trim($parts[1]);
        }

        if ($parts[0] == "--port" && $this->isValidPort(trim($parts[1]))) {
            $this->port = (int) trim($parts[1]);
        }
    }

    private function isValidHost($host): bool
    {
        if (!filter_var($host, FILTER_VALIDATE_IP)) {
            console_log(Helper::redText("Error: Invalid Host! $host could not be resolved to a valid IP address. Using 127.0.0.1 instead."));

            return false;
        }

        return true;
    }

    private function isValidPort($port): bool
    {
        if (!($port >= 1 && $port <= 65535)) {
            console_log(Helper::redText("Error: Invalid Port! $port is not a valid port. Using $this->port instead."));

            return false;
        }

        if (!$this->isPortAvailable($port)) {
            console_log(Helper::redText("Error: Port:$port is not available. Using $this->port instead."));

            return false;
        }

        return true;
    }
}
