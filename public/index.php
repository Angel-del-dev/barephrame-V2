<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);
require_once("../src/autoload.php");

use src\Router\Router;

try {
    $response = new Router()->redirect();
} catch(Throwable $e) {
    print_r($e->getMessage());
}