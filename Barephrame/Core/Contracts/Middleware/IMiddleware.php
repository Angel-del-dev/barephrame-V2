<?php

namespace Barephrame\Core\Contracts\Middleware;

use Barephrame\Core\Request\Request;
use Barephrame\Core\Response\Response;

interface IMiddleware {
    public function handle(Request $request):Response;
}