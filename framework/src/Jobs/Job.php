<?php

declare(strict_types=1);

namespace Framework\Jobs;

use Framework\Jobs\Traits\CanQueue;
use Framework\Queues\QueueableInterface;

abstract readonly class Job implements QueueableInterface
{
    use CanQueue;
}
