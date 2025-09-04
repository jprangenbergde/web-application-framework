<?php

declare(strict_types=1);

namespace Framework\Views\ViewHelpers;

use Framework\Http\Middlewares\Security\CsrfTokenMiddleware;
use Framework\Http\Requests\Request\SessionInterface;
use Override;

final readonly class CsrfToken implements ViewHelperInterface
{
    public function __construct(
        private SessionInterface $session
    ) {}

    #[Override]
    public function getName(): string
    {
        return 'csrfToken';
    }

    public function __invoke(): string
    {
        return (string) $this->session->get(CsrfTokenMiddleware::CSRF_TOKEN_NAME);
    }
}
