<?php

declare(strict_types=1);

namespace Framework\Providers\Commands;

use Framework\Consoles\Commands\CommandInterface;
use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(CommandProvider::class)]
interface CommandProviderInterface
{
    /**
     * @return array<class-string<CommandInterface>>
     */
    public function provide(): array;
}
