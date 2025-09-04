<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Bootstrappers\BootstrapperInterface;
use Framework\Events\Event;

final readonly class BootstrapperBooted extends Event implements LifecycleEventInterface
{
    public function __construct(
        public BootstrapperInterface $bootstrapper
    ) {}
}
