<?php

function CreateProjectStructure() {
    $root = __DIR__.'/../../..';
    
    $checkOrCreateFolders = ['App', 'Domains'];
    $checkOrCreateFiles = [
        'app.ini' => "[Database]\nHOST = \nUSER = \nPASSWORD = \nPORT = \nNAME = ",
        '/public/.htaccess' => "<IfModule mod_rewrite.c>\nOptions +FollowSymLinks\nRewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^ index.php [L]\n</IfModule>",
        '/public/index.php' => '' // TODO Add index.php contents
    ];

    foreach($checkOrCreateFolders as $folder) {
        $path = $root.'/'.$folder;
        if(is_dir($path)) continue;
        mkdir($path);
        echo "Folder '$folder' created\n";
    }

    foreach($checkOrCreateFiles as $file => $contents) {
        $path = $root .'/'. $file;
        if(is_file($path)) continue;

        file_put_contents($path, $contents);
        echo "File '$file' created\n";
    }
}