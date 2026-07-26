<?php

namespace Barephrame\Core\Database;

use Exception;
use PDO;
use PDOStatement;
use stdClass;

class DatabaseResult {
    protected PDOStatement|null $stmt;
    public stdClass $parameters;

    public function __construct(PDOStatement $stmt) {
        $this->stmt = $stmt;
        $this->parameters = new stdClass();
    }

    public function execute():array {
        if(is_null($this->stmt)) {
            throw new Exception('Database result is already closed');
        }

        $this->stmt->execute((array)$this->parameters);
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function close():void {
        $this->stmt = null;
    }
}