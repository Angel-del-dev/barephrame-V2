<?php

namespace src\Contracts;

use src\lib\request\Request;
use src\lib\response\Response;

interface Middleware {
    public function handle(Request $request, Response $response):Response;
}