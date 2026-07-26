<?php

namespace Barephrame\Core\Files;

use Exception;

final class Environment {
    private static array $values = [];

    private static function parseEnvironment() {
        if(!empty(self::$values)) return;
        $filePath = $_SERVER['DOCUMENT_ROOT'].'../app.ini';
        self::$values = parse_ini_file($filePath, true);
    } 

    public static function getSection(string $key):array {
        self::parseEnvironment();
        if(!isset(self::$values[$key])) {
            throw new Exception("Cannot find key '$key' in environment variables");
        }
        return self::$values[$key];
    }
}