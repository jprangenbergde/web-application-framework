<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\Configurations\ConfigurationRegistrarInterface;

trait CanRegisterConfigurations
{
    private function registerConfigurations(array $configurations): void
    {
        $this->factoryProducer
            ->get(ConfigurationRegistrarInterface::class)
            ->register($configurations);
    }

    private function registerNamedConfigurations(
        array $authConfiguration = [],
        array $consoleConfiguration = [],
        array $databaseConfiguration = [],
        array $eventConfiguration = [],
        array $routerConfiguration = [],
        array $viewConfiguration = []
    ): void {
        $this->registerConfigurations([
            'auth' => $authConfiguration,
            'console' => $consoleConfiguration,
            'database' => $databaseConfiguration,
            'event' => $eventConfiguration,
            'router' => $routerConfiguration,
            'view' => $viewConfiguration,
        ]);
    }
}
