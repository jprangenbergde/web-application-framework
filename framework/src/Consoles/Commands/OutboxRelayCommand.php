<?php

declare(strict_types=1);

namespace Framework\Consoles\Commands;

use Framework\Consoles\Commands\Attributes\Command;
use Framework\Consoles\Outputs\ConsoleOutputInterface;
use Framework\Outbox\OutboxRelay;
use Override;

#[Command('outbox:relay')]
final readonly class OutboxRelayCommand implements CommandInterface
{
    public function __construct(
        private OutboxRelay $outboxRelay,
    ) {}

    /**
     * @param array<mixed> $argv
     */
    #[Override]
    public function run(array $argv, ConsoleOutputInterface $output): void
    {
        $output->info(sprintf('Relayed %d outbox message(s).', $this->outboxRelay->relay()));
    }
}
