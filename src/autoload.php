<?php

function framework_autoload(string $name):bool {
    $file = str_replace('/src', '', __DIR__);
    $file .= '/'.str_replace('\\' , DIRECTORY_SEPARATOR, $name).'.php';

    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
};

spl_autoload_register('framework_autoload');