<?php

namespace Barephrame\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Method
{
    public function __construct(
        public string $method
    ) {}
}