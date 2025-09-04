<?php

declare(strict_types=1);

namespace Framework\Factories;

use Framework\FactoryManagers\FactoryProducerInterface;

interface FactoryInterface
{
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object;
}
