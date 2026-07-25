<?php

function framework_autoload(string $class): bool {
    $root = dirname(__DIR__);

    $list_prefixes = [
        'App\\' => $root .'/App/',
        'Domains\\' => $root . '/Domains/',
        'Barephrame\\' => $root . '/Barephrame/'
    ];

    $prefix = $class
        |> (fn(string $className) => explode('\\', $className)[0])
        |> (fn(string $className) => join("\\", [$className, '']));
    
    if(!key_exists($prefix, $list_prefixes)) {
        throw new Exception("Namespace prefix '$prefix' could not be found");
    }

    $relativeClass = substr($class, strlen($prefix))
        |> (fn(string $classPath) => str_replace('\\', DIRECTORY_SEPARATOR, $classPath))
        |> (fn(string $classPath) => $classPath . '.php');
    

    $filePath = $list_prefixes[$prefix] . $relativeClass;

    if(!file_exists($filePath)) {
        throw new Exception("File '$filePath' could not be found");
    }

    require_once($filePath);
    return true;
}

spl_autoload_register('framework_autoload');