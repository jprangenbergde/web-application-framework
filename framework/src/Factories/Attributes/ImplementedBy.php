<?php

declare(strict_types=1);

namespace Framework\Factories\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class ImplementedBy
{
    /**
     * @param class-string $class
     */
    public function __construct(
        public string $class
    ) {}
}
