<?php

declare(strict_types=1);

namespace Framework\Http\Responses;

use Override;

final readonly class HtmlResponse implements ResponseInterface
{
    public function __construct(
        private string $html,
        private int $statusCode = 200,
    ) {}

    #[Override]
    public function send(): void
    {
        header('Content-Type: text/html');
        http_response_code($this->statusCode);

        echo $this->html;
    }
}
