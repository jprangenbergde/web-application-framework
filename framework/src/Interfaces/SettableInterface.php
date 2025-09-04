<?php

declare(strict_types=1);

namespace Framework\Interfaces;

interface SettableInterface
{
    public function set(string $key, mixed $value): void;
}
