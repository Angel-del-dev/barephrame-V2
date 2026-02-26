<?php

namespace src\Middlewares;

use src\Contracts\Middleware;
use src\lib\request\Request;

class JWTBearer implements Middleware {
    public function handle(Request $request):bool {

    }
}