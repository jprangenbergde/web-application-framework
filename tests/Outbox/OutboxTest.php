<?php

declare(strict_types=1);

namespace Tests\Outbox;

use Framework\Databases\DatabaseManager;
use Framework\Emails\EmailJob;
use Framework\Outbox\OutboxQueue;
use Framework\Outbox\OutboxRelay;
use Framework\Outbox\OutboxStore;
use Framework\Queues\Database\DatabaseQueue;
use Framework\Queues\QueueableInterface;
use Framework\Queues\QueueableSerializer;
use Framework\Queues\QueueInterface;
use RuntimeException;
use Tests\DatabaseTestCase;

final class OutboxTest extends DatabaseTestCase
{
    private OutboxQueue $outbox;

    private DatabaseQueue $transport;

    private QueueableSerializer $serializer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = new QueueableSerializer();
        $this->transport = new DatabaseQueue(new DatabaseManager($this->pdo), $this->serializer);
        $this->outbox = new OutboxQueue(new OutboxStore($this->pdo), $this->serializer, $this->transport);
    }

    public function testRollbackLeavesNoOutboxMessage(): void
    {
        try {
            $this->transactions->transactional(function (): void {
                $this->addItem('business data');
                $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));

                throw new RuntimeException('boom');
            });
        } catch (RuntimeException) {
        }

        self::assertSame(0, $this->itemCount());
        self::assertSame(0, $this->rowCount('outbox_messages'));
    }

    public function testCommitPersistsBusinessDataAndMessageTogether(): void
    {
        $this->transactions->transactional(function (): void {
            $this->addItem('business data');
            $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));
        });

        self::assertSame(1, $this->itemCount());
        self::assertSame(1, $this->rowCount('outbox_messages'));
        self::assertSame(0, $this->rowCount('queue_items'), 'Transport is only fed by the relay');
    }

    public function testRelayForwardsMessagesOnceAndMarksThemProcessed(): void
    {
        $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));
        $relay = $this->relay($this->transport);

        self::assertSame(1, $relay->relay());
        self::assertSame(1, $this->rowCount('queue_items'));
        self::assertSame(1, $this->rowCount('outbox_messages WHERE processed_at IS NOT NULL'));

        self::assertSame(0, $relay->relay(), 'Second run must not forward again');
        self::assertSame(1, $this->rowCount('queue_items'));

        $job = $this->outbox->pop();
        self::assertEquals(new EmailJob('a@b.c', 'Hi', 'Body'), $job);
    }

    public function testRelayForwardsHigherPriorityFirst(): void
    {
        $store = new OutboxStore($this->pdo);
        $store->add(EmailJob::class, ['email' => 'low@b.c', 'subject' => 's', 'content' => 'c'], 0);
        $store->add(EmailJob::class, ['email' => 'high@b.c', 'subject' => 's', 'content' => 'c'], 2);

        $forwarded = [];
        $this->relay($this->recordingTransport($forwarded))->relay();

        self::assertSame(['high@b.c', 'low@b.c'], array_map(static fn(EmailJob $job) => $job->email, $forwarded));
    }

    public function testFailingTransportRollsBackClaimAndRecordsFailure(): void
    {
        $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));
        $relay = $this->relay($this->failingTransport());

        self::assertSame(0, $relay->relay());

        $row = $this->pdo->query('SELECT processed_at, attempts, last_error FROM outbox_messages')->fetch();
        self::assertNull($row['processed_at'], 'Message must stay pending');
        self::assertSame(1, $row['attempts']);
        self::assertSame('transport down', $row['last_error']);
    }

    public function testRelaySkipsMessagesThatExceededMaxAttempts(): void
    {
        $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));
        $relay = $this->relay($this->failingTransport(), maxAttempts: 2);

        $relay->relay();
        $relay->relay();
        $relay->relay();

        self::assertSame(2, (int) $this->pdo->query('SELECT attempts FROM outbox_messages')->fetchColumn());
    }

    public function testRelayRecoversOnceTransportWorksAgain(): void
    {
        $this->outbox->append(new EmailJob('a@b.c', 'Hi', 'Body'));

        $this->relay($this->failingTransport())->relay();
        self::assertSame(1, $this->relay($this->transport)->relay());
        self::assertSame(1, $this->rowCount('queue_items'));
    }

    private function relay(QueueInterface $transport, int $maxAttempts = 5): OutboxRelay
    {
        return new OutboxRelay(
            new OutboxStore($this->pdo),
            $this->serializer,
            $this->transactions,
            $transport,
            maxAttempts: $maxAttempts,
        );
    }

    private function rowCount(string $tableAndWhere): int
    {
        return (int) $this->pdo->query("SELECT COUNT(*) FROM $tableAndWhere")->fetchColumn();
    }

    private function failingTransport(): QueueInterface
    {
        return new class implements QueueInterface {
            public function append(QueueableInterface $queueable): void
            {
                throw new RuntimeException('transport down');
            }

            public function pop(): ?QueueableInterface
            {
                return null;
            }
        };
    }

    /**
     * @param array<QueueableInterface> $forwarded
     */
    private function recordingTransport(array &$forwarded): QueueInterface
    {
        return new class ($forwarded) implements QueueInterface {
            /** @param array<QueueableInterface> $forwarded */
            public function __construct(private array &$forwarded) {}

            public function append(QueueableInterface $queueable): void
            {
                $this->forwarded[] = $queueable;
            }

            public function pop(): ?QueueableInterface
            {
                return null;
            }
        };
    }
}
