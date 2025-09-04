<?php

declare(strict_types=1);

namespace Framework\Application\Worker;

use Framework\Application\ApplicationHandlerInterface;
use Override;

final readonly class DefaultWorker implements ApplicationWorkerInterface
{
    #[Override]
    public function work(ApplicationHandlerInterface $application): void
    {
        $application->handle();
    }
}
