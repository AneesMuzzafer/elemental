<?php

namespace Core\Database;

use PDO;

class Database {

    public DatabaseConnection $dbConnection;
    public PDO $pdo;

    public function __construct(DatabaseConnection $dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->pdo = $this->dbConnection->getPDO();
    }

    public function __call($method, $args){
        return call_user_func_array([$this->pdo, $method], $args);
    }

}
