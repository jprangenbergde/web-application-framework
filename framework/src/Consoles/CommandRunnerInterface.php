<?php

declare(strict_types=1);

namespace Framework\Consoles;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Console::class)]
interface CommandRunnerInterface
{
    public function run(array $arguments): void;
}
