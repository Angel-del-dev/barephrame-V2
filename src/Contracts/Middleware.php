<?php

namespace src\Contracts;

use src\lib\request\Request;

interface Middleware {
    public function handle(Request $request): boole;
}