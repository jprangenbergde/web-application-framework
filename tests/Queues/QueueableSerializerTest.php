<?php

declare(strict_types=1);

namespace Tests\Queues;

use Framework\Queues\QueueableInterface;
use Framework\Queues\QueuePriority;
use Framework\Queues\QueueableSerializer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

final class QueueableSerializerTest extends TestCase
{
    public function testRoundtripWithConstructorSpread(): void
    {
        $serializer = new QueueableSerializer();

        $data = $serializer->serialize(new ConstructorJob('a@b.c', 'Hi'));

        self::assertSame(ConstructorJob::class, $data['type']);
        self::assertSame(['email' => 'a@b.c', 'subject' => 'Hi'], $data['payload']);
        self::assertSame(QueuePriority::High->value, $data['priority']);

        $job = $serializer->deserialize($data['type'], $data['payload']);

        self::assertEquals(new ConstructorJob('a@b.c', 'Hi'), $job);
    }

    public function testRoundtripWithFromArray(): void
    {
        $serializer = new QueueableSerializer();

        $data = $serializer->serialize(new ArrayableJob('payload'));
        $job = $serializer->deserialize($data['type'], $data['payload']);

        self::assertEquals(new ArrayableJob('payload'), $job);
    }

    public function testRejectsTypesThatAreNotQueueable(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new QueueableSerializer())->deserialize(stdClass::class, []);
    }
}

final readonly class ConstructorJob implements QueueableInterface
{
    public function __construct(public string $email, public string $subject) {}

    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::High;
    }
}

final readonly class ArrayableJob implements QueueableInterface, \Framework\Interfaces\ArrayableInterface
{
    public function __construct(public string $value) {}

    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::Low;
    }

    public function toArray(): array
    {
        return ['wrapped' => $this->value];
    }

    public static function fromArray(array $data): self
    {
        return new self($data['wrapped']);
    }
}
