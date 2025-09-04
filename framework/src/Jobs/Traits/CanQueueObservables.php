<?php

declare(strict_types=1);

namespace Framework\Jobs\Traits;

use Framework\Events\ObservableInterface;
use Framework\Events\Traits\CanOccur;
use Framework\Queues\QueueableInterface;
use RuntimeException;

trait CanQueueObservables
{
    use CanQueue;
    use CanOccur;

    public function execute(): void
    {
        $implements = class_implements(static::class);

        if (!isset($implements[QueueableInterface::class], $implements[ObservableInterface::class])) {
            throw new RuntimeException(
                sprintf(
                    '%s class must implement %s and %s',
                    CanQueueObservables::class,
                    QueueableInterface::class,
                    ObservableInterface::class
                )
            );
        }

        self::occur($this);
    }
}
