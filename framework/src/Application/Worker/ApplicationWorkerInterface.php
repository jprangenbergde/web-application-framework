<?php

declare(strict_types=1);

namespace Framework\Application\Worker;

use Framework\Application\ApplicationHandlerInterface;

interface ApplicationWorkerInterface
{
    public function work(ApplicationHandlerInterface $application): void;
}
