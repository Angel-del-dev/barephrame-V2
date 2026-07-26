<?php

namespace Barephrame\Core\Database;

use Barephrame\Core\Database\Languages\Firebird;
use Barephrame\Core\Database\Languages\Mysql;
use Barephrame\Core\Database\Languages\Postgres;
use Exception;

final class Connections {
    protected static array $connections = [];

    public static function connect(
        string $host, string $name, 
        string $user, string $password,
        DatabaseTypes $type
    ):void 
    {
        $engine = self::getEngineFromType($type);
        print_r($type);
        exit;
    }

    public static function use(string $key):void
    {
        if(!self::isConnected($key)) {
            throw new Exception("Database alias '{$key}' does not exist");
        }

        // TODO Return connection
    }

    public static function isConnected(string $key):bool
    {
        return isset(self::$connections[$key]);
    }

    public static function mainConnection(string $key, DatabaseTypes $type):void
    {}

    protected static function getEngineFromType(DatabaseTypes $type)
    {
        $engines = [
            DatabaseTypes::MYSQL->name => Mysql::class,
            DatabaseTypes::FIREBIRD->name => Firebird::class,
            DatabaseTypes::POSTGRES->name => Postgres::class
        ];

        if(!isset($engines[$type->name])) {
            throw new Exception("Database engine '{$type->name}' not supported");           
        }

        return $engines[$type->name];
    }
}