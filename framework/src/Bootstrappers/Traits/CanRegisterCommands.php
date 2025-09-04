<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\Consoles\CommandRegistrarInterface;

trait CanRegisterCommands
{
    private function registerCommands(array $commandClasses): void
    {
        $this->factoryProducer
            ->get(CommandRegistrarInterface::class)
            ->register($commandClasses);
    }
}
