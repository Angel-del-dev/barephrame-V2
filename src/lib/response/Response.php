<?php

namespace src\lib\response;

final class Response {
    public function __construct(
        public int $status = 200,
        public string $message = 'OK',
        public string $data = '',
        public int $retry_after = 0
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

    // Rate limiting header
    public function set_retry_after(int $seconds) {
        $this->retry_after = $seconds;
    }
}