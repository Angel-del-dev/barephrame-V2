<?php

require_once("../Barephrame/autoload.php");

use Barephrame\Core\Response\Common\InternalServerError;
use Barephrame\Core\Response\Renderer;
use Barephrame\Core\Router\Router;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);

$response = null;

try {
    $response = new Router()->redirect();
} catch(Throwable $e) {
    $response = InternalServerError::send();
    // TODO Add error to log
}

Renderer::send($response);