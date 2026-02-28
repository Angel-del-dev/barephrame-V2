<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class MethodNotAllowed implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(401, 'Method Not Allowed');
        return $response;
    }
}