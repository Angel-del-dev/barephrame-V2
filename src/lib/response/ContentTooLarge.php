<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class ContentTooLarge implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(413, 'Content Too Large');
        return $response;
    }
}