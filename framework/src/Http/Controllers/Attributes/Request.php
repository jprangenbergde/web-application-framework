<?php

declare(strict_types=1);

namespace Framework\Http\Controllers\Attributes;

use Attribute;
use Framework\Http\Requests\RequestInterface;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Request
{
    /**
     * @param class-string<RequestInterface> $requestClass
     */
    public function __construct(
        public string $requestClass,
    ) {}
}
