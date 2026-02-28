<?php

namespace src\lib\response;

final class Response {
    public function __construct(
        private int $status = 200,
        private string $message = 'OK',
        private string $data = ''
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
}