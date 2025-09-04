<?php

declare(strict_types=1);

namespace App\Bootstrappers;

use Framework\Bootstrappers\BootstrapperInterface;
use Framework\Bootstrappers\Traits\CanBootstrap;
use Framework\Bootstrappers\Traits\CanRegisterConfigurations;
use Framework\FactoryManagers\FactoryProducerInterface;
use Override;

final readonly class AppBootstrapper implements BootstrapperInterface
{
    use CanBootstrap;
    use CanRegisterConfigurations;

    public function __construct(
        private FactoryProducerInterface $factoryProducer
    ) {}

    #[Override]
    public function boot(): void
    {
        $this->registerNamedConfigurations(
            require __DIR__ . '/../../config/auth.php',
            require __DIR__ . '/../../config/console.php',
            require __DIR__ . '/../../config/database.php',
            require __DIR__ . '/../../config/event.php',
            require __DIR__ . '/../../config/router.php',
            require __DIR__ . '/../../config/view.php'
        );
    }
}
