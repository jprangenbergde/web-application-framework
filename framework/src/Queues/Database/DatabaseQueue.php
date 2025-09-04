<?php

declare(strict_types=1);

namespace Framework\Queues\Database;

use Framework\Databases\DatabaseManager;
use Framework\Interfaces\ArrayableInterface;
use Framework\Queues\QueueableInterface;
use Framework\Queues\QueueInterface;
use Override;

final readonly class DatabaseQueue implements QueueInterface
{
    public function __construct(
        private DatabaseManager $databaseManager
    ) {}

    #[Override]
    public function append(QueueableInterface $queueable): void
    {
        $this->databaseManager->save(
            QueueRow::new(
                $queueable->getQueuePriority()->value,
                $queueable::class,
                $queueable instanceof ArrayableInterface ? $queueable->toArray() : (array) $queueable
            )
        );
    }

    #[Override]
    public function pop(): ?QueueableInterface
    {
        $queueRow = $this->databaseManager->select(QueueRow::class, orderBy: [['priority', 'DESC']]);
        if (!$queueRow) {
            return null;
        }

        $this->databaseManager->delete($queueRow);

        if ($queueRow->type instanceof ArrayableInterface) {
            return $queueRow->type::fromArray($queueRow->data);
        }

        return new $queueRow->type(...$queueRow->data);
    }
}
