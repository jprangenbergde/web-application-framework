<?php

declare(strict_types=1);

namespace Framework\Listeners\Consoles\Commands;

use Framework\Events\EventManagers\EventAttacherInterface;
use Framework\Events\LifecycleEvents\CommandStarted;
use Framework\Listeners\ObserverInterface;
use Override;

final readonly class StartedOutputListener implements ObserverInterface
{
    #[Override]
    public function observe(EventAttacherInterface $attacher): void
    {
        $attacher->attach(
            CommandStarted::class,
            fn(CommandStarted $commandStarted) => $this->onCommandStarted($commandStarted)
        );
    }

    private function onCommandStarted(CommandStarted $commandStarted): void
    {
        $commandStarted->consoleOutput->info(
            sprintf(
                '[%s] Started at %s',
                $commandStarted->command::class,
                date('d.m.Y H:i:s')
            )
        );
    }
}
