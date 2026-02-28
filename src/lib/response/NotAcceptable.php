<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class NotAcceptable implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(406, 'Not Acceptable');
        return $response;
    }
}