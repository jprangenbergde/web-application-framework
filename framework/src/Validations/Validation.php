<?php

declare(strict_types=1);

namespace Framework\Validations;

use Override;

final readonly class Validation implements ValidationInterface
{
    public function __construct(
        private array $messages
    ) {}

    public static function errors(array $messages): self
    {
        return new self($messages);
    }

    public static function ok(): self
    {
        return new self([]);
    }

    #[Override]
    public function messages(): array
    {
        return $this->messages;
    }

    #[Override]
    public function valid(): bool
    {
        return $this->messages === [];
    }

    #[Override]
    public function invalid(): bool
    {
        return $this->messages !== [];
    }
}
