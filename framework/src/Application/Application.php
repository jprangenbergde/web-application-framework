<?php

declare(strict_types=1);

namespace Framework\Application;

use Framework\Application\Worker\ApplicationWorkerInterface;
use Framework\Application\Worker\DefaultWorker;
use Framework\Bootstrappers\BootstrapperInterface;
use Framework\Bootstrappers\FinalizeBootstrapper;
use Framework\Bootstrappers\FrameworkBootstrapper;
use Framework\Events\LifecycleEvents\ApplicationBooted;
use Framework\Events\LifecycleEvents\ApplicationBootStarted;
use Framework\Events\LifecycleEvents\BootstrapperBooted;
use Framework\Events\LifecycleEvents\RequestCreated;
use Framework\Events\LifecycleEvents\ResponseCreated;
use Framework\Events\LifecycleEvents\ResponseSent;
use Framework\Events\LifecycleEvents\RouterCreated;
use Framework\Events\LifecycleEvents\RouterMatched;
use Framework\Events\LifecycleEvents\RunFinished;
use Framework\Events\LifecycleEvents\RunStarted;
use Framework\FactoryManagers\FactoryManagerInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use Framework\Http\Middlewares\MiddlewareChain;
use Framework\Http\Requests\RequestInterface;
use Framework\Http\Responses\ResponseInterface;
use Framework\Http\Routers\RouteMatch;
use Framework\Http\Routers\RouteMatcherInterface;
use Override;

final readonly class Application implements ApplicationInterface
{
    /**
     * @param array<class-string<BootstrapperInterface>> $bootstrapperClasses
     * @param class-string<ApplicationWorkerInterface> $applicationWorker
     */
    public function __construct(
        private FactoryManagerInterface $factoryManager,
        private array $bootstrapperClasses = [],
        private array $middlewareClasses = [],
        private string $applicationWorker = DefaultWorker::class,
    ) {}

    #[Override]
    public function boot(): ApplicationRunnerInterface
    {
        FrameworkBootstrapper::bootstrap();
        ApplicationBootStarted::occur();

        foreach ($this->bootstrapperClasses as $bootstrapperClass) {
            /** @var BootstrapperInterface $bootstrapper */
            $bootstrapper = $this->producer()->get($bootstrapperClass);
            $bootstrapper->boot();
            BootstrapperBooted::occur($bootstrapper);
        }

        FinalizeBootstrapper::bootstrap();
        ApplicationBooted::occur();

        return $this;
    }

    #[Override]
    public function flush(): ApplicationRunnerInterface
    {
        $this->factoryManager->flush();
        gc_collect_cycles();

        return $this;
    }

    #[Override]
    public function producer(): FactoryProducerInterface
    {
        return $this->factoryManager;
    }

    #[Override]
    public function run(): void
    {
        $this->producer()->get($this->applicationWorker)->work($this);
    }

    /**
     * @return void
     */
    #[Override]
    public function handle(): void
    {
        RunStarted::occur();

        $request = $this->producer()->produce(RequestInterface::class);
        RequestCreated::occur($request);

        $routeMatcher = $this->producer()->get(RouteMatcherInterface::class);
        RouterCreated::occur($routeMatcher);

        $routeMatch = $routeMatcher->match($request);
        RouterMatched::occur($routeMatch);

        $response = $this->handleRequest($routeMatch);
        ResponseCreated::occur($response);

        $response->send();
        ResponseSent::occur($response);

        RunFinished::occur();
    }

    private function handleRequest(RouteMatch $routeMatch): ResponseInterface
    {
        $middlewareChain = new MiddlewareChain(
            array_map(
                fn(string $middlewareClass) => $this->factoryManager->get($middlewareClass),
                $this->middlewareClasses,
            ),
            $routeMatch->getRequestHandler(),
        );

        return $middlewareChain->handle($routeMatch->getRequest());
    }
}
