<?php

declare(strict_types=1);

namespace Framework\Http;

use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\ResponseInterface;

interface RequestHandlerInterface
{
    public function handle(RequestInterface $request): ResponseInterface;
}
