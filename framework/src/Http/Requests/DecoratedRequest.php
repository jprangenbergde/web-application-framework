<?php

declare(strict_types=1);

namespace Framework\Http\Requests;

use Framework\Http\Requests\Traits\CanDecorateRequestInterface;

abstract readonly class DecoratedRequest implements RequestInterface
{
    use CanDecorateRequestInterface;
}
