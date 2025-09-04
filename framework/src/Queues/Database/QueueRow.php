<?php

declare(strict_types=1);

namespace Framework\Queues\Database;

use Framework\Interfaces\ArrayableInterface;
use Framework\Models\ModelInterface;
use Framework\Queues\QueueableInterface;
use Override;

final class QueueRow implements ModelInterface
{
    /**
     * @param class-string<QueueableInterface> $type
     */
    public function __construct(
        public int $id,
        public readonly int $priority,
        public readonly string $type,
        public readonly array $data
    ) {}

    /**
     * @param class-string<QueueableInterface> $type
     */
    public static function new(int $priority, string $type, array $data): self
    {
        return new self(0, $priority, $type, $data);
    }

    #[Override]
    public function getId(): int
    {
        return $this->id;
    }

    #[Override]
    public static function getIdColumnName(): string
    {
        return 'id';
    }

    #[Override]
    public static function getTableName(): string
    {
        return 'queue_items';
    }

    #[Override]
    public function toArray(): array
    {
        return (array) $this;
    }

    #[Override]
    public static function fromArray(array $data): ArrayableInterface
    {
        return new self(...$data);
    }
}
