<?php

namespace Barephrame\Core\Database;

use Exception;
use PDO;

abstract class Connection {
    protected PDO|null $dbh;

    public function __construct() {
        $this->dbh = null;
    }

    public function __destruct()
    {
        $this->dbh = null;
    }

    public function statement(string $query):DatabaseResult {
        if(is_null($this->dbh)) {
            throw new Exception('Database connection is closed');
        }

        $pdoStatement = $this->dbh->prepare($query);
        if($pdoStatement === false) {
            throw new Exception("Could not execute query '{$query}'");
        }
        return new DatabaseResult($pdoStatement);
    }

    abstract public function createConnection(
        string $host, string $name, 
        string $user, string $password, int $port
    );
}