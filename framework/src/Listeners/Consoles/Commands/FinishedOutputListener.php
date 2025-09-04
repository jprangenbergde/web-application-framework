<?php

declare(strict_types=1);

namespace Framework\Listeners\Consoles\Commands;

use Framework\Events\EventManagers\EventAttacherInterface;
use Framework\Events\LifecycleEvents\CommandFinished;
use Framework\Listeners\ObserverInterface;
use Override;

final readonly class FinishedOutputListener implements ObserverInterface
{
    #[Override]
    public function observe(EventAttacherInterface $attacher): void
    {
        $attacher->attach(
            CommandFinished::class,
            fn(CommandFinished $commandFinished) => $this->onCommandFinished($commandFinished)
        );
    }

    private function onCommandFinished(CommandFinished $commandFinished): void
    {
        $commandFinished->consoleOutput->info(
            sprintf(
                '[%s] Finished at %s',
                $commandFinished->command::class,
                date('d.m.Y H:i:s')
            )
        );
    }
}
