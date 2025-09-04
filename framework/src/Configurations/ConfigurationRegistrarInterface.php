<?php

declare(strict_types=1);

namespace Framework\Configurations;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Configuration::class)]
interface ConfigurationRegistrarInterface
{
    public function register(array $configurations): void;
}
