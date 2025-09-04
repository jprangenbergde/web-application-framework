<?php

declare(strict_types=1);

namespace Framework\Factories\Attributes;

use Attribute;
use Framework\Factories\FactoryInterface;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class ProducedBy
{
    /**
     * @param class-string<FactoryInterface> $factoryClass
     */
    public function __construct(
        public string $factoryClass
    ) {}
}
