<?php

namespace Barephrame\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Version
{
    public function __construct(
        public int $version = 0
    ) {}
}