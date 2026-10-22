<?php

declare(strict_types=1);

namespace Framework\Outbox;

final readonly class OutboxMessage
{
    /**
     * @param array<mixed> $payload
     */
    public function __construct(
        public int $id,
        public string $type,
        public array $payload,
    ) {}
}
