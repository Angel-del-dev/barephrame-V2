<?php

namespace Barephrame\Core\Response;

final class Renderer {
    public static function send(Response $response):void {
        http_response_code($response->status);

        if(trim($response->message) !== '') {
            header("X-Message: {$response->message}");
        }

        if($response->retryAfter > 0) {
            header("Retry-After: {$response->retryAfter}");
        }

        if(trim($response->contentType) !== '') {
            header("Content-Type: {$response->contentType}");
        }

        if(trim($response->data) !== '') {
            echo trim($response->data);
        }
    }
}