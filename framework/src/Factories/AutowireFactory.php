<?php

declare(strict_types=1);

namespace Framework\Factories;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Dependencies\Dependency;
use Framework\Dependencies\DependencyResolver;
use Framework\Factories\Attributes\Config;
use Framework\Factories\Attributes\Produce;
use Framework\FactoryManagers\FactoryProducerInterface;
use RuntimeException;
use Throwable;
use Override;

/**
 * @see Extended-Abstract-Factory-Pattern
 */
final readonly class AutowireFactory implements FactoryInterface
{
    public function __construct(
        private DependencyResolver $dependencyResolver
    ) {}

    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $dependencies = $this->dependencyResolver->resolveConstructorDependencies($class);
        if ($dependencies === []) {
            return new $class();
        }

        $parameters = [];
        foreach ($dependencies as $dependency) {
            try {
                $parameters[] = $this->resolve($dependency, $factoryProducer);
            } catch (Throwable) {
                throw new RuntimeException(
                    sprintf(
                        '%s: Could not resolve dependency "$%s" with type "%s"',
                        $class,
                        $dependency->name,
                        $dependency->type
                    )
                );
            }
        }

        return new $class(...$parameters);
    }

    private function resolve(Dependency $dependency, FactoryProducerInterface $factoryProducer): mixed
    {
        if ($dependency->hasAttribute(Produce::class)) {
            return $factoryProducer->produce($dependency->type);
        }

        $config = $dependency->getAttribute(Config::class);
        if ($config instanceof Config) {
            return $factoryProducer->get(ConfigurationRepositoryInterface::class)->get(
                $config->key,
                $config->default
            );
        }

        if (!$dependency->exists()) {
            return $factoryProducer->get($dependency->name);
        }

        return $factoryProducer->get($dependency->type);
    }
}
