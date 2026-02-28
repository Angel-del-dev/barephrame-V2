<?php

namespace src\lib\response;

final class Response {
    public function __construct(
        public int $status = 200,
        public string $message = 'OK',
        public string $data = '',
        public int $retry_after = 0,
        public string $content_type = 'application/json'
    ) {}
    public function status(int $status, string $message):Response {
        $this->status = $status;
        $this->message = $message;
        return $this;
    }

    public function set_data(mixed $data):Response {
        $this->data = json_encode($data);
        return $this;
    }

    public function set_content_type(string $content_type):Response {
        $this->content_type = $content_type;
        return $this;
    }

    // Rate limiting
    public function set_retry_after(int $seconds):Response {
        $this->retry_after = $seconds;
        return $this;
    }
}