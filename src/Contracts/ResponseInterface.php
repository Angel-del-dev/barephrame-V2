<?php

namespace src\Contracts;

use src\lib\response\Response;

interface ResponseInterface {
    public function send(): Response;
}