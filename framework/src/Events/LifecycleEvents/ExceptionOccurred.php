<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;
use Throwable;

final readonly class ExceptionOccurred extends Event implements LifecycleEventInterface
{
    public function __construct(
        public Throwable $throwable
    ) {}
}
