<?php

declare(strict_types=1);

namespace Framework\FactoryManagers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(FactoryManagerInterface::class)]
interface FactoryRegistrarInterface
{
    public function register(array $factoryClasses): void;
}
