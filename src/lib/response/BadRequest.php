<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class BadRequest implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(400, 'Bad Request');
        return $response;
    }
}