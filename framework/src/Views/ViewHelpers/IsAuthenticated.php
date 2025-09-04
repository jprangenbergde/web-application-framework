<?php

declare(strict_types=1);

namespace Framework\Views\ViewHelpers;

use Framework\Factories\Attributes\Config;
use Framework\Http\Requests\RequestInterface;
use Override;

final readonly class IsAuthenticated implements ViewHelperInterface
{
    public function __construct(
        #[Config('auth.sessionKey')]
        private string $sessionKey,
        private RequestInterface $request
    ) {}

    #[Override]
    public function getName(): string
    {
        return 'isAuthenticated';
    }

    public function __invoke(): bool
    {
        return $this->request
            ->getSession()
            ->has($this->sessionKey);
    }
}
