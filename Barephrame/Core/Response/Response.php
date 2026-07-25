<?php

namespace Barephrame\Core\Response;

final class Response {
    public function __construct(
        public int $status = 200,
        public string $message = 'OK',
        public string $data = '',
        public int $retryAfter = 0,
        public string $contentType = 'application/json'
    ) {}
    public function status(int $status, string $message):Response {
        $this->status = $status;
        $this->message = $message;
        return $this;
    }

    public function setData(mixed $data):Response {
        $this->data = json_encode($data);
        return $this;
    }

    public function setContentType(string $contentType):Response {
        $this->contentType = $contentType;
        return $this;
    }

    // Rate limiting
    public function setRetryAfter(int $seconds):Response {
        $this->retryAfter = $seconds;
        return $this;
    }
}