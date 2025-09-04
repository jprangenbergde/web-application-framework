<?php

declare(strict_types=1);

namespace Framework\Http\Responses;

use Override;

final readonly class EmptyResponse implements ResponseInterface
{
    public function __construct(
        private int $statusCode = 200,
    ) {}

    #[Override]
    public function send(): void
    {
        http_response_code($this->statusCode);
    }
}
