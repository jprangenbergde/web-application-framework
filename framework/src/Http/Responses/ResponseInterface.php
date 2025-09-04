<?php

declare(strict_types=1);

namespace Framework\Http\Responses;

interface ResponseInterface
{
    public function send(): void;
}
