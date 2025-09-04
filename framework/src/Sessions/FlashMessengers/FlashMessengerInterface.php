<?php

declare(strict_types=1);

namespace Framework\Sessions\FlashMessengers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(FlashMessenger::class)]
interface FlashMessengerInterface
{
    public function addMessage(FlashMessage $message): void;

    /**
     * @param array<FlashMessage> $messages
     */
    public function addMessages(array $messages): void;

    /**
     * @return array<FlashMessage>
     */
    public function getMessages(): array;
}
