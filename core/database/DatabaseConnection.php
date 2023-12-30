<?php

namespace Core\Database;

use PDO;

class DatabaseConnection
{
    protected PDO $pdo;
    public function __construct()
    {
        $servername = "127.0.0.1:33060";
        $username = "root";
        $password = "root";

        try {
            $this->pdo = new PDO("mysql:host=$servername;dbname=laracast", $username, $password);
            // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getPDO()
    {
        return $this->pdo;
    }
}
