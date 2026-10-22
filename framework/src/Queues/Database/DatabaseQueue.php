<?php

declare(strict_types=1);

namespace Framework\Queues\Database;

use Framework\Databases\DatabaseManager;
use Framework\Queues\QueueableInterface;
use Framework\Queues\QueueableSerializer;
use Framework\Queues\QueueInterface;
use Override;

final readonly class DatabaseQueue implements QueueInterface
{
    public function __construct(
        private DatabaseManager $databaseManager,
        private QueueableSerializer $serializer,
    ) {}

    #[Override]
    public function append(QueueableInterface $queueable): void
    {
        ['type' => $type, 'payload' => $payload, 'priority' => $priority] = $this->serializer->serialize($queueable);

        $this->databaseManager->save(QueueRow::new($priority, $type, $payload));
    }

    #[Override]
    public function pop(): ?QueueableInterface
    {
        $queueRow = $this->databaseManager->select(QueueRow::class, orderBy: [['priority', 'DESC']]);
        if (!$queueRow) {
            return null;
        }

        $this->databaseManager->delete($queueRow);

        return $this->serializer->deserialize($queueRow->type, $queueRow->data);
    }
}
