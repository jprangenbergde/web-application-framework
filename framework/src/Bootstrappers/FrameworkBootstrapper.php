<?php

declare(strict_types=1);

namespace Framework\Bootstrappers;

use Framework\Bootstrappers\Traits\CanBootstrap;
use Framework\Bootstrappers\Traits\CanRegisterConfigurations;
use Framework\Bootstrappers\Traits\CanRegisterFactories;
use Framework\Bootstrappers\Traits\CanRegisterObservers;
use Framework\Databases\PDOFactory;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Http\Requests\Request;
use Framework\Http\Requests\Request\CookiesInterface;
use Framework\Http\Requests\Request\PayloadInterface;
use Framework\Http\Requests\Request\QueryParametersInterface;
use Framework\Http\Requests\Request\ServerInterface;
use Framework\Http\Requests\Request\SessionInterface;
use Framework\Http\Requests\RequestInterface;
use Framework\Listeners\BenchmarkListener;
use PDO;
use Override;

final readonly class FrameworkBootstrapper implements BootstrapperInterface
{
    use CanBootstrap;
    use CanRegisterConfigurations;
    use CanRegisterFactories;
    use CanRegisterObservers;

    public function __construct(
        private FactoryProducerInterface $factoryProducer,
    ) {}

    #[Override]
    public function boot(): void
    {
        $this->registerNamedConfigurations(
            consoleConfiguration: require __DIR__ . '/../../config/console.php',
            eventConfiguration: require __DIR__ . '/../../config/event.php',
            routerConfiguration: require __DIR__ . '/../../config/router.php',
            viewConfiguration: require __DIR__ . '/../../config/view.php'
        );

        $this->registerFactories(
            [
                PDO::class => PDOFactory::class,
                RequestInterface::class => static fn() => Request::fromGlobals(),
                PayloadInterface::class => static fn(FactoryProducerInterface $factoryProducer) => $factoryProducer->get(RequestInterface::class)->getPayload(),
                QueryParametersInterface::class => static fn(FactoryProducerInterface $factoryProducer) => $factoryProducer->get(RequestInterface::class)->getQueryParameters(),
                SessionInterface::class => static fn(FactoryProducerInterface $factoryProducer) => $factoryProducer->get(RequestInterface::class)->getSession(),
                CookiesInterface::class => static fn(FactoryProducerInterface $factoryProducer) => $factoryProducer->get(RequestInterface::class)->getCookies(),
                ServerInterface::class => static fn(FactoryProducerInterface $factoryProducer) => $factoryProducer->get(RequestInterface::class)->getServer(),
            ]
        );

        $this->registerObservers([
            $this->factoryProducer->get(BenchmarkListener::class),
        ]);
    }
}
