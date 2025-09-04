<?php

declare(strict_types=1);

namespace Framework\Factories\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class Config
{
    public function __construct(
        public string $key,
        public mixed $default = null
    ) {}
}
