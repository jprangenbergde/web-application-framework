<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;
use Framework\Http\Routers\RouterInterface;

final readonly class RouterCreated extends Event implements LifecycleEventInterface
{
    public function __construct(
        public RouterInterface $router
    ) {}
}
