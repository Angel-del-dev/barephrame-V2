<?php

function make(string $name):void {
    $root = __DIR__.'/../../..';
    if(!is_dir($root.'/pages')) {
        echo "Initial structure for the project not found, use exec.php --scaffold to fix it and re-run the make command\n";
        return;
    }

    $check_or_create_folders = [
        '/http',
        '/http/controllers'
    ];

    $check_or_create_files = [
        "/http/controllers/{$name}Controller.php" => "<?php\nnamespace http\controllers;\nclass {$name}Controller {}"
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