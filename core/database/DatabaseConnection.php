<?php

namespace Core\Database;

use Core\Config\Config;
use PDO;

class DatabaseConnection
{
    protected PDO $pdo;
    public function __construct(Config $config)
    {
        $driver = $config->db["driver"];
        $host = $config->db["host"];
        $port = $config->db["port"];
        $database = $config->db["database"];
        $username = $config->db["username"];
        $password = $config->db["password"];

        try {
            $this->pdo = new PDO("$driver:host=$host:$port;dbname=$database", $username, $password);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
