<?php

declare(strict_types=1);

namespace Framework\Interfaces;

interface UnsettableInterface
{
    public function unset(string $key): void;
}
