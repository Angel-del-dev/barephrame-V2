<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class Unauthorized implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(401, 'Unauthorized');
        return $response;
    }
}