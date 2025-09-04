<?php

declare(strict_types=1);

namespace Framework\Consoles\Commands;

use Framework\Consoles\Commands\Attributes\Command;
use Framework\Consoles\Outputs\ConsoleOutputInterface;
use Framework\Jobs\JobManager;
use Override;

#[Command('dequeue')]
final readonly class DequeueCommand implements CommandInterface
{
    public function __construct(
        private JobManager $jobManager,
    ) {}

    #[Override]
    public function run(array $argv, ConsoleOutputInterface $output): void
    {
        $this->jobManager->dequeue();
    }
}
