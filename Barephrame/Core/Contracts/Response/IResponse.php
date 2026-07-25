<?php

namespace Barephrame\Core\Contracts\Response;

use Barephrame\Core\Response\Response;

interface IResponse {
    public static function send():Response;
}