<?php

declare(strict_types=1);

namespace Framework\Queues;

use Framework\Interfaces\ArrayableInterface;
use Framework\Interfaces\FromArrayInterface;
use InvalidArgumentException;

final readonly class QueueableSerializer
{
    /**
     * @return array{type: class-string<QueueableInterface>, payload: array<mixed>, priority: int}
     */
    public function serialize(QueueableInterface $queueable): array
    {
        return [
            'type' => $queueable::class,
            'payload' => $queueable instanceof ArrayableInterface ? $queueable->toArray() : (array) $queueable,
            'priority' => $queueable->getQueuePriority()->value,
        ];
    }

    /**
     * @param array<mixed> $payload
     */
    public function deserialize(string $type, array $payload): QueueableInterface
    {
        if (!is_subclass_of($type, QueueableInterface::class)) {
            throw new InvalidArgumentException(
                sprintf('%s is not a %s and must not be instantiated from stored data', $type, QueueableInterface::class)
            );
        }

        if (is_subclass_of($type, FromArrayInterface::class)) {
            /** @var QueueableInterface */
            return $type::fromArray($payload);
        }

        return new $type(...$payload);
    }
}
