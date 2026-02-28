<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class UnsupportedMediaType implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(415, 'Unsuported Media Type');
        return $response;
    }
}