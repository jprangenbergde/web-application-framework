<?php

declare(strict_types=1);

namespace Framework\Interfaces;

interface GettableInterface
{
    public function get(string $key): mixed;

    public function has(string $key): bool;
}
