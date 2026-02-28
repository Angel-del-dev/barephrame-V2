<?php

namespace src\lib\response;
use src\Contracts\ResponseInterface;


final class RateLimitExceeded implements ResponseInterface {
    public function send():Response {
        $response = new Response()
            ->status(429, 'Too Many Requests');
        return $response;
    }
}