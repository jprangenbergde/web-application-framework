<?php

declare(strict_types=1);

namespace Framework\Outbox;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Databases\Transactions\TransactionManagerInterface;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Queues\Database\DatabaseQueue;
use Framework\Queues\QueueableSerializer;
use Framework\Queues\QueueInterface;
use Override;

final readonly class OutboxRelayFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $configuration = $factoryProducer->get(ConfigurationRepositoryInterface::class);

        /** @var class-string<QueueInterface> $transportClass */
        $transportClass = $configuration->get('database.outbox.transport', DatabaseQueue::class);

        return new OutboxRelay(
            $factoryProducer->get(OutboxStore::class),
            $factoryProducer->get(QueueableSerializer::class),
            $factoryProducer->get(TransactionManagerInterface::class),
            $factoryProducer->get($transportClass),
            $this->positiveInt($configuration->get('database.outbox.batchSize'), 100),
            $this->positiveInt($configuration->get('database.outbox.maxAttempts'), 5),
        );
    }

    private function positiveInt(mixed $value, int $default): int
    {
        return is_int($value) && $value > 0 ? $value : $default;
    }
}
