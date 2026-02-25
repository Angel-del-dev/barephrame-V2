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
    }
];
foreach($flags as $flag) {
    if(!isset($allowed_flags[$flag->name])) {
        echo sprintf("Flag --%s not available\n", $flag->name);
        continue;
    }
    $allowed_flags[$flag->name]($flag->values);
}