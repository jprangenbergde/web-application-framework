<?php

declare(strict_types=1);

namespace Framework\Queues;

enum QueuePriority: int
{
    case Low = 0;
    case Medium = 1;
    case High = 2;
}
