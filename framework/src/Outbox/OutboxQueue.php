<?php

declare(strict_types=1);

namespace Framework\Outbox;

use Framework\Queues\QueueableInterface;
use Framework\Queues\QueueableSerializer;
use Framework\Queues\QueueInterface;
use Override;

/**
 * Schreibt Nachrichten in die Outbox-Tabelle (innerhalb der aktuellen Transaktion).
 * Das Weiterleiten an den eigentlichen Transport übernimmt der OutboxRelay.
 */
final readonly class OutboxQueue implements QueueInterface
{
    public function __construct(
        private OutboxStore $store,
        private QueueableSerializer $serializer,
        private QueueInterface $transport,
    ) {}

    #[Override]
    public function append(QueueableInterface $queueable): void
    {
        ['type' => $type, 'payload' => $payload, 'priority' => $priority] = $this->serializer->serialize($queueable);

        $this->store->add($type, $payload, $priority);
    }

    #[Override]
    public function pop(): ?QueueableInterface
    {
        return $this->transport->pop();
    }
}
