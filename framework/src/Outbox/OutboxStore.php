<?php

declare(strict_types=1);

namespace Framework\Outbox;

use DateTimeImmutable;
use PDO;

final readonly class OutboxStore
{
    public function __construct(private PDO $connection) {}

    /**
     * @param array<mixed> $payload
     */
    public function add(string $type, array $payload, int $priority): void
    {
        $this->connection
            ->prepare(
                'INSERT INTO outbox_messages (type, payload, priority, created_at) VALUES (?, ?, ?, ?)'
            )
            ->execute([
                $type,
                json_encode($payload, JSON_THROW_ON_ERROR),
                $priority,
                $this->now(),
            ]);
    }

    /**
     * @return array<OutboxMessage>
     */
    public function fetchPending(int $limit, int $maxAttempts): array
    {
        $statement = $this->connection->prepare(
            'SELECT id, type, payload, priority, attempts FROM outbox_messages
             WHERE processed_at IS NULL AND attempts < ?
             ORDER BY priority DESC, id ASC
             LIMIT ' . max(1, $limit)
        );
        $statement->execute([$maxAttempts]);

        $messages = [];
        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $messages[] = new OutboxMessage(
                (int) $row['id'],
                (string) $row['type'],
                (array) json_decode((string) $row['payload'], true, flags: JSON_THROW_ON_ERROR),
                (int) $row['priority'],
                (int) $row['attempts'],
            );
        }

        return $messages;
    }

    /**
     * Markiert die Nachricht atomar als verarbeitet. Nur ein Relay kann sie erfolgreich beanspruchen.
     */
    public function claim(int $id): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE outbox_messages SET processed_at = ? WHERE id = ? AND processed_at IS NULL'
        );
        $statement->execute([$this->now(), $id]);

        return $statement->rowCount() === 1;
    }

    public function recordFailure(int $id, string $error): void
    {
        $this->connection
            ->prepare('UPDATE outbox_messages SET attempts = attempts + 1, last_error = ? WHERE id = ?')
            ->execute([$error, $id]);
    }

    private function now(): string
    {
        return (new DateTimeImmutable())->format('Y-m-d H:i:s');
    }
}
