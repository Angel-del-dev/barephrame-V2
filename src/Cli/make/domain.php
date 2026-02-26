<?php

function make(string $name):void {
    $root = __DIR__.'/../../..';
    if(!is_dir($root.'/pages')) {
        echo "Initial structure for the project not found, use exec.php --scaffold to fix it and re-run the make command\n";
        return;
    }

    $check_or_create_folders = [
        '/domains',
        "/domains/{$name}",
        "/domains/{$name}/DTOs",
        '/http',
        '/http/controllers'
    ];

    $check_or_create_files = [
        "/domains/{$name}/{$name}.service.php" => 'TEST CONTENT',
        "/domains/{$name}/{$name}.repository.php" => 'TEST CONTENT',
        "/domains/{$name}/{$name}.php" => 'TEST CONTENT',
        "/http/controllers/{$name}.controller.php" => 'TEST CONTENT'
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