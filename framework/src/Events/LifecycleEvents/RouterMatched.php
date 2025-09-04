<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;
use Framework\Http\Routers\RouteMatch;

final readonly class RouterMatched extends Event implements LifecycleEventInterface
{
    public function __construct(
        public RouteMatch $routeMatch
    ) {}
}
