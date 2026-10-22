<?php

declare(strict_types=1);

namespace Framework\Outbox;

use Framework\Databases\Transactions\TransactionManagerInterface;
use Framework\Factories\Attributes\ProducedBy;
use Framework\Queues\QueueableSerializer;
use Framework\Queues\QueueInterface;
use Throwable;

/**
 * Leitet ausstehende Outbox-Nachrichten an den Transport weiter.
 *
 * Liegt der Transport in derselben Datenbank, sind Claim und Append atomar.
 * Bei externen Transporten gilt at-least-once: Consumer müssen idempotent sein.
 */
#[ProducedBy(OutboxRelayFactory::class)]
final readonly class OutboxRelay
{
    public function __construct(
        private OutboxStore $store,
        private QueueableSerializer $serializer,
        private TransactionManagerInterface $transactionManager,
        private QueueInterface $transport,
        private int $batchSize = 100,
        private int $maxAttempts = 5,
    ) {}

    /**
     * @return int Anzahl erfolgreich weitergeleiteter Nachrichten
     */
    public function relay(): int
    {
        $relayed = 0;

        foreach ($this->store->fetchPending($this->batchSize, $this->maxAttempts) as $message) {
            try {
                $forwarded = $this->transactionManager->transactional(function () use ($message): bool {
                    if (!$this->store->claim($message->id)) {
                        return false;
                    }

                    $this->transport->append(
                        $this->serializer->deserialize($message->type, $message->payload)
                    );

                    return true;
                });
            } catch (Throwable $throwable) {
                $this->store->recordFailure($message->id, $throwable->getMessage());

                continue;
            }

            if ($forwarded) {
                ++$relayed;
            }
        }

        return $relayed;
    }
}
