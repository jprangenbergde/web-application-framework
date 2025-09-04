<?php

declare(strict_types=1);

namespace Framework\Configurations;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Configuration::class)]
interface ConfigurationRepositoryInterface
{
    public function get(string $key, mixed $default = null): mixed;
}
