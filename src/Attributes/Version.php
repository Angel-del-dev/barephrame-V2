<?php

namespace src\Attributes;
use Attribute;

#[Attribute(Attribute::TARGET_FUNCTION | Attribute::IS_REPEATABLE)]
class Version
{
    public function __construct(
        public int $version = 0
    ){}
}