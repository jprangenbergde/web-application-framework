<?php

namespace App\Events;

use Framework\Events\Event;
use Framework\Jobs\Traits\CanQueueObservables;
use Framework\Queues\QueueableInterface;
use Framework\Queues\QueuePriority;
use Override;

final readonly class Registered extends Event implements QueueableInterface
{
    use CanQueueObservables;

    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
    ) {}

    #[Override]
    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::Low;
    }
}
