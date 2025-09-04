<?php

declare(strict_types=1);

namespace Framework\Sessions\FlashMessengers;

final readonly class FlashMessage
{
    public function __construct(
        public string $text,
        public FlashMessageType $type = FlashMessageType::Error
    ) {}

    /**
     * @param array<string> $messages
     * @return array<self>
     */
    public static function fromMessages(array $messages, FlashMessageType $type = FlashMessageType::Error): array
    {
        return array_map(fn(array $message) => new self(array_shift($message), $type), $messages);
    }

    public static function fromText(string $text, FlashMessageType $type = FlashMessageType::Error): self
    {
        return new self($text, $type);
    }
}
