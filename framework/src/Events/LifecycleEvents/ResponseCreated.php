<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;
use Framework\Http\Responses\ResponseInterface;

final readonly class ResponseCreated extends Event implements LifecycleEventInterface
{
    public function __construct(
        public ResponseInterface $response
    ) {}
}
