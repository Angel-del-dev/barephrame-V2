<?php

namespace Barephrame\Core\Database\Languages;

use Barephrame\Core\Database\Connection;
use Override;
use PDO;
use PDOException;

class Postgres extends Connection {
    public function __construct()
    {
        parent::__construct();
    }

    #[Override]
    public function createConnection(string $host, string $name, string $user, string $password, int $port = 0)
    {
        if($port === 0) {
            $port = 5432;
        }
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s',
            $host, $port, $name, $user, $password
        );

        $this->dbh = new PDO($dsn);
    }
}