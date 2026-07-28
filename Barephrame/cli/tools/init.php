<?php

function CreateProjectStructure() {
    $root = __DIR__.'/../../..';
    
    $checkOrCreateFolders = ['App', 'Domains', 'public'];
    $checkOrCreateFiles = [
        'app.ini' => "[Database]\nHOST = \nUSER = \nPASSWORD = \nPORT = \nNAME = ",
        'public/.htaccess' => "<IfModule mod_rewrite.c>\nOptions +FollowSymLinks\nRewriteEngine On\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule ^ index.php [L]\n</IfModule>",
        'public/index.php' => "<?php\nrequire_once('../Barephrame/autoload.php');\nuse Barephrame\Core\Response\Common\InternalServerError;\nuse Barephrame\Core\Response\Renderer;\nuse Barephrame\Core\Router\Router;\nuse Barephrame\Core\Log\Log;\nini_set('display_errors', '1');\nini_set('display_startup_errors', '1');\nerror_reporting(-1);\n\$response = null;\ntry {\n\$response = new Router()->redirect();\n} catch(Throwable \$e) {\n\$response = InternalServerError::send();\nLog::error(sprintf(\n\"%s in '%s' line: %d\",\n\$e->getMessage(),\n\$e->getFile(),\n\$e->getLine()\n));\n}\nRenderer::send(\$response);"
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