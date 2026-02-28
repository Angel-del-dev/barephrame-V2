<?php

if(php_sapi_name() !== 'cli') {
    exit("Only CLI\n");
}

$flags = [];

$current_flag_position = -1;

for($i = 1 ; $i < $argc ; $i++) {
    $argument = $argv[$i];
    if(substr($argument, 0, 2) === '--') {
        $flag = new stdClass();
        $flag->name = substr($argument, 2);
        $flag->values = [];
        $flags[] = $flag;
        $current_flag_position++;
    } else {
        $flags[$current_flag_position]->values[] = $argument;
    }
}

// Flag execution


$allowed_flags = [
    'cache-routes' => function(array $_):void {
        require_once(__DIR__.'/../Cache/cache_routes.php');
    },
    'scaffold' => function(array $_):void {
        $root =  __DIR__.'/../..';
        $check_or_create_folders = ['/pages', '/domains', '/http', '/http/controllers', '/public'];
        $check_or_create_files = [
            '.env' => "# Database\nDATABASE_HOST =\nDATABASE_USER =\nDATABASE_PASSWORD =\nDATABASE_PORT =\nDATABASE_NAME =\n# Email\nEMAIL_HOST =\nEMAIL_USER =\nEMAIL_PASSWORD =\nEMAIL_PORT=\n# Environment\nPRODUCTION = false",
            '/public/.htaccess' => "<IfModule mod_rewrite.c>\nOptions +FollowSymLinks\nRewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^ index.php [L]\n</IfModule>",
            '/public/index.php' => "<?php\nini_set('display_errors', '1');\nini_set('display_startup_errors', '1');\nerror_reporting(-1);\nrequire_once(\"{$_SERVER['DOCUMENT_ROOT']}../src/autoload.php\");"
        ];

        foreach($check_or_create_folders as $folder) {
            $path = $root.$folder;
            if(is_dir($path)) continue;
            mkdir($path);
            echo "Folder generated {$folder}\n";
        }

        foreach($check_or_create_files as $file => $contents) {
            $path = $root.'/'.$file;
            if(is_file($path)) continue;
            file_put_contents($path, $contents);
            echo "File generated {$file}\n";
        }
    },
    'make' => function(array $values):void {
        $available_types = array_filter(
            array_map(fn($file) => str_replace('.php', '', $file), scandir(__DIR__.'/make')),
            fn($file) => !in_array($file, ['.', '..'])
        );

        if(count($values) < 2) {
            echo "Invalid syntax for flag make\nExample: exec.php --make {type} {name}\nAvailable types: ".implode(', ', $available_types)."\n";
            return;
        }
        $type = strtolower($values[0]);
        $name = ucfirst(strtolower($values[1]));
        if(!in_array($type, $available_types)) {
            echo "Type '{$type}' not available\nAvailable types: ".implode(', ', $available_types)."\n";
            return;
        }
        require_once(__DIR__."/make/{$type}.php");
        make($name);
    }
];
foreach($flags as $flag) {
    if(!isset($allowed_flags[$flag->name])) {
        echo sprintf("Flag --%s not available\n", $flag->name);
        continue;
    }
    $allowed_flags[$flag->name]($flag->values);
}