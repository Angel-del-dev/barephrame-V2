<?php

namespace Barephrame\Core\Log;

final class Log {
    public static function success(string $contents): void {
        self::store('Success', $contents);
    }

    public static function warning(string $contents): void {
        self::store('Warning', $contents);
    }

    public static function information(string $contents): void {
        self::store('Information', $contents);
    }

    public static function store(string $key, string $contents):void {
        $rootPath = $_SERVER['DOCUMENT_ROOT'].'../logs';
        if(!is_dir($rootPath)) {
            mkdir($rootPath);
        }
        
        $fileName = sprintf(
            '%s.log',
            date('Y-m-d')
        );
        
        $line = sprintf("%s [%s] %s\n", date('H:i:s'), $key, $contents);

        $handle = fopen($rootPath.'/'.$fileName, 'a');
        if($handle) {
            fwrite($handle, $line);
            fclose($handle);
        }
    }
}