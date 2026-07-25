<?php

namespace Barephrame\Core\Response\Common;

use Barephrame\Core\Contracts\Response\IResponse;
use Barephrame\Core\Response\Response;

class NoContent implements IResponse {
    public static function send():Response {
        return new Response()->status(204, 'No Content');
    }
}