<?php

declare(strict_types=1);

namespace Framework\Consoles\Outputs;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Override;

final readonly class ConsoleOutputFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $configuration = $factoryProducer->get(ConfigurationRepositoryInterface::class);

        return $factoryProducer->get(
            (string) $configuration->get('console.outputClass', NullConsoleOutput::class)
        );
    }
}
