<?php

declare(strict_types=1);

namespace Framework\Http\Responses;

use Override;

final readonly class RedirectResponse implements ResponseInterface
{
    public function __construct(
        private string $location,
    ) {}

    #[Override]
    public function send(): void
    {
        header('Location: ' . $this->location);
    }
}
