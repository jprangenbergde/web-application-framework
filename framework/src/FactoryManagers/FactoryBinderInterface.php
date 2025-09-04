<?php

declare(strict_types=1);

namespace Framework\FactoryManagers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(FactoryManager::class)]
interface FactoryBinderInterface
{
    public function bind(string $name, object $instance): void;
}
