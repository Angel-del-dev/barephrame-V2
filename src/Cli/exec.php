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
    'cache-routes' => function(array $values):void {
        require_once(__DIR__.'/../Cache/cache_routes.php');
    },
    'scaffold' => function(array $_):void {
        $root =  __DIR__.'/../..';
        $check_or_create_folders = ['/pages'];
        $check_or_create_files = [
            '.env' => "# Database\nDATABASE_HOST =\nDATABASE_USER =\nDATABASE_PASSWORD =\nDATABASE_PORT =\nDATABASE_NAME =\n# Email\nEMAIL_HOST =\nEMAIL_USER =\nEMAIL_PASSWORD =\nEMAIL_PORT=\n"
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
    }
];
foreach($flags as $flag) {
    if(!isset($allowed_flags[$flag->name])) {
        echo sprintf("Flag --%s not available\n", $flag->name);
        continue;
    }
    $allowed_flags[$flag->name]($flag->values);
}