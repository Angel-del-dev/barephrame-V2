<?php

use Barephrame\Core\Router\Router;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);

require_once("../Barephrame/autoload.php");

try {
    $response = new Router()->redirect();

    print_r($response);
    exit;
} catch(Throwable $e) {
    print_r($e->getMessage()); // TODO Better exceptions
}