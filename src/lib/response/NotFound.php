<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class NotFound implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(404, 'Not Found');
        return $response;
    }
}