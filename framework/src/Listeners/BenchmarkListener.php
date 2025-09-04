<?php

declare(strict_types=1);

namespace Framework\Listeners;

use Framework\Events\Event;
use Framework\Events\EventManagers\EventAttacherInterface;
use Framework\Events\LifecycleEvents\ApplicationBooted;
use Framework\Events\LifecycleEvents\ApplicationBootStarted;
use Framework\Events\LifecycleEvents\LifecycleEventInterface;
use Framework\Events\LifecycleEvents\ResponseCreated;
use Framework\Events\LifecycleEvents\RouterCreated;
use Framework\Events\LifecycleEvents\RouterMatched;
use Framework\Events\LifecycleEvents\RunFinished;
use Framework\Events\LifecycleEvents\WorkerHandlerStarted;
use Override;

final class BenchmarkListener implements ObserverInterface
{
    private array $occurredTimes = [];

    #[Override]
    public function observe(EventAttacherInterface $attacher): void
    {
        $attacher->attach(
            ApplicationBootStarted::class,
            function (): void {
                $this->occurredTimes = [];
            },
        );

        $attacher->attach(
            WorkerHandlerStarted::class,
            function (): void {
                $this->occurredTimes = [];
            },
        );

        $attacher->attach(
            LifecycleEventInterface::class,
            function (Event $event): void {
                $this->occurredTimes[$event::class] = microtime(true);
            },
            -1,
        );

        $attacher->attach(
            RunFinished::class,
            function (): void {
                $this->output(ApplicationBootStarted::class, ApplicationBooted::class);
                $this->output(ApplicationBootStarted::class, RunFinished::class);
                $this->output(RouterCreated::class, RouterMatched::class);
                $this->output(RouterMatched::class, ResponseCreated::class);
            },
            -1,
        );
    }

    private function output(string $fromEventClass, string $toEventClass): void
    {
        if (!array_key_exists($fromEventClass, $this->occurredTimes)) {
            return;
        }

        if (!array_key_exists($toEventClass, $this->occurredTimes)) {
            return;
        }

        echo sprintf(
            '<!-- %s ms: %s -->',
            str_pad(
                (string) round(($this->occurredTimes[$toEventClass] - $this->occurredTimes[$fromEventClass]) * 1000, 4),
                6,
            ),
            basename(str_replace('\\', '/', $toEventClass)),
        );
    }
}
