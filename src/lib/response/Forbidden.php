<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class Forbidden implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(403, 'Forbidden');
        return $response;
    }
}