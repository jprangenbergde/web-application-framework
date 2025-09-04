<?php

declare(strict_types=1);

namespace Framework\Consoles\Commands\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Command
{
    public function __construct(
        public string $commandName
    ) {}
}
