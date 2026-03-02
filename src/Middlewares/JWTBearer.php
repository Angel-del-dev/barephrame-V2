<?php

namespace src\Middlewares;

use src\Contracts\Middleware;
use src\lib\request\Request;
use src\lib\response\Response;

class JWTBearer implements Middleware {
    public function handle(Request $request, Response $response):Response {
        return $response;
    }
}