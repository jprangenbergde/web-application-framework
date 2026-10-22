<?php

declare(strict_types=1);

namespace Framework\Queues;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Outbox\OutboxQueue;
use Framework\Outbox\OutboxStore;
use Framework\Queues\Database\DatabaseQueue;
use Override;

final readonly class QueueFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $configuration = $factoryProducer->get(ConfigurationRepositoryInterface::class);

        /** @var class-string<QueueInterface> $transportClass */
        $transportClass = $configuration->get('database.outbox.transport', DatabaseQueue::class);
        $transport = $factoryProducer->get($transportClass);

        if (!$configuration->get('database.outbox.enabled', false)) {
            return $transport;
        }

        return new OutboxQueue(
            $factoryProducer->get(OutboxStore::class),
            $factoryProducer->get(QueueableSerializer::class),
            $transport,
        );
    }
}
