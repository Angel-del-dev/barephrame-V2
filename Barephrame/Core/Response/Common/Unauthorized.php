<?php

namespace Barephrame\Core\Response\Common;

use Barephrame\Core\Contracts\Response\IResponse;
use Barephrame\Core\Response\Response;

class Unauthorized implements IResponse {
    public static function send():Response {
        return new Response()->status(401, 'Unauthorized');
    }
}