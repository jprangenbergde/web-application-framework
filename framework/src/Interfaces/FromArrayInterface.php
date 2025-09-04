<?php

declare(strict_types=1);

namespace Framework\Interfaces;

interface FromArrayInterface
{
    public static function fromArray(array $data): self;
}
