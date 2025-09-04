<?php

declare(strict_types=1);

namespace Framework\Queues;

interface QueueableInterface
{
    public function getQueuePriority(): QueuePriority;
}
