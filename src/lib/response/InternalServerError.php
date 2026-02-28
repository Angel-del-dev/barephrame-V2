<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class InternalServerError implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(500, 'Internal Server Error');
        return $response;
    }
}