<?php

declare(strict_types=1);

namespace Framework\Sessions\FlashMessengers;

use Framework\Http\Requests\Request\SessionInterface;
use Override;

final readonly class FlashMessenger implements FlashMessengerInterface
{
    public function __construct(
        private SessionInterface $session
    ) {}

    #[Override]
    public function addMessage(FlashMessage $message): void
    {
        $messages = $this->session->get('flashMessenger')['messages'] ?? [];
        $messages[] = $message;

        $this->session->set('flashMessenger', [
            'messages' => $messages,
        ]);
    }

    #[Override]
    public function addMessages(array $messages): void
    {
        foreach ($messages as $message) {
            $this->addMessage($message);
        }
    }

    #[Override]
    public function getMessages(): array
    {
        $messages = $this->session->get('flashMessenger')['messages'] ?? [];
        $this->session->unset('flashMessenger');

        return $messages;
    }
}
