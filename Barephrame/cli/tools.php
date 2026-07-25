<?php

if(php_sapi_name() !== 'cli') {
    exit('This script is CLI only');
}

$flags = [];
$toolsRootPath = __DIR__.'/tools';


// Group flag names with their values

$current_flag_position = -1;

for($i = 1 ; $i < $argc ; $i++) {
    $argument = $argv[$i];

    $isArgumentName = substr($argument, 0, 2) === '--';
    if($isArgumentName) {
        $flag = new stdClass();
        $flag->name = substr($argument, 2);
        $flag->values = [];
        $flags[] = $flag;
        $current_flag_position++;
    } else {
        if(count($flags) === 0) continue;
        $flags[$current_flag_position]->values[] = $argument;
    }
}

// Flag execution

$allowed_flags = [
    'init' => function(array $_) use($toolsRootPath):void {
        require_once($toolsRootPath.'/init.php');
        CreateProjectStructure();
    },
    'compile-routes' => function(array $_) use($toolsRootPath):void {
        require_once($toolsRootPath.'/compile-routes.php');
        CompileRoutes();
    },
    'watch' => function(array $_) use($toolsRootPath):void {
        $rootEndpoints = __DIR__.'/../../App';

        $last = filemtime($rootEndpoints);

        require_once($toolsRootPath.'/compile-routes.php');

        while(true) {        
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootEndpoints, FilesystemIterator::SKIP_DOTS)
            );

            $scanLast = $last;

            foreach ($iterator as $file) {
                $scanLast = max($scanLast, $file->getMTime());
            }
            if($last < $scanLast) {
                CompileRoutes();
                $last = $scanLast;
            }
            sleep(1);
        }
        print_r($toolsRootPath);
    }
];

foreach($flags as $flag) {
    if(!isset($allowed_flags[$flag->name])) {
        echo sprintf("Flag --%s not available\n", $flag->name);
        break;
    }
    $allowed_flags[$flag->name]($flag->values);
}