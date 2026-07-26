<?php

namespace Barephrame\Core\Database\Languages;

use Barephrame\Core\Database\Connection;
use Exception;
use Override;

class Mysql extends Connection {
    #[Override]
    public function createConnection(string $host, string $name, string $user, string $password)
    {
        throw new Exception("Not implemented");
    }
}