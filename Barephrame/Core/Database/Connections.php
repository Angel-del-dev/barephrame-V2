<?php

namespace Barephrame\Core\Database;

use Barephrame\Core\Database\Languages\Firebird;
use Barephrame\Core\Database\Languages\Mysql;
use Barephrame\Core\Database\Languages\Postgres;
use Barephrame\Core\Files\Environment;
use Exception;

final class Connections {
    protected static array $connections = [];

    public static function connect(
        string $host, string $name, 
        string $user, string $password,
        int $port, DatabaseTypes $type
    ):Connection 
    {
        $engine = self::getEngineFromType($type);

        $engine->createConnection($host, $name, $user, $password, $port);
        return $engine;
    }

    public static function use(string $key):Connection
    {
        if(!self::isConnected($key)) {
            throw new Exception("Database alias '{$key}' does not exist");
        }

        return self::$connections[$key];
    }

    public static function isConnected(string $key):bool
    {
        return isset(self::$connections[$key]);
    }

    public static function mainConnection(DatabaseTypes $type):Connection
    {
        if(self::isConnected('main')) {
            return self::use('main');
        }

        $section = Environment::getSection('Database');
        $port = intval($section['PORT']) ?? 0;
        return self::connect(
            $section['HOST'], $section['NAME'],
            $section['USER'], $section['PASSWORD'],
            $port, $type
        );
    }

    protected static function getEngineFromType(DatabaseTypes $type):Connection
    {
        $engines = [
            DatabaseTypes::MYSQL->name => Mysql::class,
            DatabaseTypes::FIREBIRD->name => Firebird::class,
            DatabaseTypes::POSTGRES->name => Postgres::class
        ];

        if(!isset($engines[$type->name])) {
            throw new Exception("Database engine '{$type->name}' not supported");           
        }

        return new $engines[$type->name]();
    }
}