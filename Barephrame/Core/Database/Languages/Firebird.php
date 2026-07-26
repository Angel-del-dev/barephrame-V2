<?php

namespace Barephrame\Core\Database\Languages;

use Barephrame\Core\Database\Connection;
use Override;
use PDO;

class Firebird extends Connection {
    #[Override]
    public function createConnection(
        string $host, string $name, 
        string $user, string $password, 
        int $port = 0
    ) {
        if($port === 0) {
            $port = 3050;
        }
        
        $dsn = sprintf(
            'firebird:dbname=%s/%d:%s',
            $host, $port, $name
        );
        $this->dbh = new PDO($dsn, $user, $password);
    }
}