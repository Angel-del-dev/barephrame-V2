<?php

namespace Barephrame\Core\Response\Common;

use Barephrame\Core\Contracts\Response\IResponse;
use Barephrame\Core\Response\Response;

class Conflict implements IResponse {
    public static function send():Response {
        return new Response()->status(409, 'Conflict');
    }
}