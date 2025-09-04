<?php

declare(strict_types=1);

namespace Framework\Bootstrappers;

use Framework\Bootstrappers\Traits\CanBootstrap;
use Framework\Bootstrappers\Traits\CanRegisterCommands;
use Framework\Bootstrappers\Traits\CanRegisterObservers;
use Framework\Bootstrappers\Traits\CanRegisterRoutes;
use Framework\Bootstrappers\Traits\CanRegisterViewHelpers;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Providers\Commands\CommandProviderInterface;
use Framework\Providers\Observers\ObserverProviderInterface;
use Framework\Providers\Routes\RouteProviderInterface;
use Framework\Providers\ViewHelpers\ViewHelperProviderInterface;
use Override;

final readonly class FinalizeBootstrapper implements BootstrapperInterface
{
    use CanBootstrap;
    use CanRegisterCommands;
    use CanRegisterObservers;
    use CanRegisterRoutes;
    use CanRegisterViewHelpers;

    public function __construct(
        private FactoryProducerInterface $factoryProducer,
    ) {}

    #[Override]
    public function boot(): void
    {
        $this->registerCommands(
            $this->factoryProducer
                ->get(CommandProviderInterface::class)
                ->provide()
        );

        $this->registerObservers(
            $this->factoryProducer
                ->get(ObserverProviderInterface::class)
                ->provide()
        );

        $this->registerRoutes(
            $this->factoryProducer
                ->get(RouteProviderInterface::class)
                ->provide()
        );

        $this->registerViewHelpers(
            $this->factoryProducer
                ->get(ViewHelperProviderInterface::class)
                ->provide()
        );
    }
}
