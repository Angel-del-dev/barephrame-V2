<?php

namespace Barephrame\Core\Database\Languages;

use Barephrame\Core\Database\Connection;
use Override;
use PDO;

class Mysql extends Connection {
    #[Override]
    public function createConnection(
        string $host, string $name, 
        string $user, string $password, 
        int $port = 0
    ) {
        if($port === 0) {
            $port = 3306;
        }

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;port=%d',
            $host, $name, $port
        );
        $this->dbh = new PDO($dsn, $user, $password);
    }
}