<?php

declare(strict_types=1);

namespace Framework\Queues;

use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Queues\Database\DatabaseQueue;
use Override;

final readonly class QueueFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        return $factoryProducer->get(DatabaseQueue::class);
    }
}
