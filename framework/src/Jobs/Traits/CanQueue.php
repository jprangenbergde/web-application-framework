<?php

declare(strict_types=1);

namespace Framework\Jobs\Traits;

use Framework\Jobs\JobManager;
use Framework\Queues\QueueableInterface;
use RuntimeException;

trait CanQueue
{
    public static function queue(mixed ...$parameters): void
    {
        $implements = class_implements(static::class);

        if (!isset($implements[QueueableInterface::class])) {
            throw new RuntimeException(
                sprintf(
                    '%s class must implement %s',
                    CanQueueObservables::class,
                    QueueableInterface::class,
                )
            );
        }

        instance(JobManager::class)->queue(new static(...$parameters));
    }
}
