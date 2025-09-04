<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Http\Controllers\ControllerInterface;
use RuntimeException;
use Override;

final readonly class RouterFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $configuration = $factoryProducer->get(ConfigurationRepositoryInterface::class);

        $pageNotFoundControllerClass = (string) $configuration->get('router.controllers.pageNotFoundControllerClass', '');
        if (!is_subclass_of($pageNotFoundControllerClass, ControllerInterface::class)) {
            throw new RuntimeException(
                sprintf(
                    'Configuration `router.controllers.pageNotFoundControllerClass` is not a subclass of `%s`',
                    ControllerInterface::class
                )
            );
        }

        return new Router(
            $factoryProducer,
            $pageNotFoundControllerClass,
        );
    }
}
