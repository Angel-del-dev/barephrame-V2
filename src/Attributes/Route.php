<?php

namespace src\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class Route
{
    public function __construct(
        public string $path,
        public array $middlewares = []
    ){}
}