<?php

namespace Barephrame\Core\Response\Common;

use Barephrame\Core\Contracts\Response\IResponse;
use Barephrame\Core\Response\Response;

class NotFound implements IResponse {
    public static function send():Response {
        return new Response()->status(404, 'Not Found');
    }
}