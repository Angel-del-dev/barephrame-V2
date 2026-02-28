<?php

namespace src\lib\request;

class Request {
    public string  $endpoint;
    public string  $method;
    public array   $headers;
    public ?string $content_type;
    public array   $query_params;
    public array   $body;
    public string  $ip;
    public function __construct() {
        $this->endpoint = $_SERVER['REDIRECT_URL'] ?? '/';
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $this->headers = getallheaders();

        $this->content_type = $this->headers['Content-Type'] ?? null;
        $this->ip = $_SERVER['REMOTE_ADDR'];
        $this->query_params = $_GET ?? [];
        $this->body = [];
        if($this->content_type !== null) {
            switch($this->content_type) {
                case 'application/x-www-form-urlencoded':
                    $this->body = $_POST;
                break;
                default:
                    $this->body = json_decode(file_get_contents("php://input"));
                break;
            }
        }
    }
}