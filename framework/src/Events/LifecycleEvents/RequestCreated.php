<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;
use Framework\Http\Requests\RequestInterface;

final readonly class RequestCreated extends Event implements LifecycleEventInterface
{
    public function __construct(
        public RequestInterface $request
    ) {}
}
