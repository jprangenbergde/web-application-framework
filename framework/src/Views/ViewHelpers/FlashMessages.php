<?php

declare(strict_types=1);

namespace Framework\Views\ViewHelpers;

use Framework\Sessions\FlashMessengers\FlashMessage;
use Framework\Sessions\FlashMessengers\FlashMessengerInterface;
use Override;

final readonly class FlashMessages implements ViewHelperInterface
{
    public function __construct(
        private FlashMessengerInterface $flashMessenger
    ) {}

    #[Override]
    public function getName(): string
    {
        return 'flashMessages';
    }

    /**
     * @return array<FlashMessage>
     */
    public function __invoke(): array
    {
        return $this->flashMessenger->getMessages();
    }
}
