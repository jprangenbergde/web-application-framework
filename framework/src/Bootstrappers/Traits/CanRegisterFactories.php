<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\FactoryManagers\FactoryRegistrarInterface;

trait CanRegisterFactories
{
    private function registerFactories(array $factoryClasses): void
    {
        $this->factoryProducer
            ->get(FactoryRegistrarInterface::class)
            ->register($factoryClasses);
    }
}
