<?php

require_once("../Barephrame/autoload.php");

use Barephrame\Core\Response\Common\InternalServerError;
use Barephrame\Core\Response\Renderer;
use Barephrame\Core\Router\Router;
use Barephrame\Core\Log\Log;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(-1);

$response = null;

try {
    $response = new Router()->redirect();
} catch(Throwable $e) {
    $response = InternalServerError::send();
    Log::error(sprintf(
        "%s in '%s' line: %d",
        $e->getMessage(),
        $e->getFile(),
        $e->getLine()
    ));
}

Renderer::send($response);