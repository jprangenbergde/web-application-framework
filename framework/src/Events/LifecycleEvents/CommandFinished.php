<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Consoles\Commands\CommandInterface;
use Framework\Consoles\Outputs\ConsoleOutputInterface;
use Framework\Events\Event;

final readonly class CommandFinished extends Event implements LifecycleEventInterface
{
    public function __construct(
        public CommandInterface $command,
        public ConsoleOutputInterface $consoleOutput
    ) {}
}
