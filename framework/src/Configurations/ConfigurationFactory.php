<?php

declare(strict_types=1);

namespace Framework\Configurations;

use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Override;

final readonly class ConfigurationFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        return new Configuration([]);
    }
}
