<?php

declare(strict_types=1);

namespace Framework\Application\Worker;

use Framework\Application\ApplicationHandlerInterface;
use Framework\Events\LifecycleEvents\WorkerHandlerFinished;
use Framework\Events\LifecycleEvents\WorkerHandlerStarted;
use Override;

final readonly class FrankenPHPWorker implements ApplicationWorkerInterface
{
    #[Override]
    public function work(ApplicationHandlerInterface $application): void
    {
        $handler = static function () use ($application): void {
            WorkerHandlerStarted::occur();
            $application->handle();
            WorkerHandlerFinished::occur();
        };

        $maxRequests = (int) ($_SERVER['MAX_REQUESTS'] ?? 0);
        for ($requestsMade = 0; !$maxRequests || $requestsMade < $maxRequests; ++$requestsMade) {
            $keepRunning = \frankenphp_handle_request($handler);
            $application->flush();
            if (!$keepRunning) {
                break;
            }
        }
    }
}
