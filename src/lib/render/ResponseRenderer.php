<?php

namespace src\lib\render;

use src\lib\response\Response;

class ResponseRenderer {
    private Response $response;
    public function __construct(Response $response) {
        $this->response = $response;
    }

    public function send():void {
        http_response_code($this->response->status);
        if(trim($this->response->message) !== '') {
            header("X-Message: {$this->response->message}");
        }
        if($this->response->retry_after > 0) {
            header("Retry-After: {$this->response->retry_after}");
        }
        
        if(trim($this->response->data) !== '') {
            echo $this->response->data;
        }
    }
}