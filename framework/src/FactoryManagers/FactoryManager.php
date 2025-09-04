<?php

declare(strict_types=1);

namespace Framework\FactoryManagers;

use Closure;
use Framework\Dependencies\DependencyResolver;
use Framework\Factories\Attributes\ImplementedBy;
use Framework\Factories\Attributes\ProducedBy;
use Framework\Factories\AutowireFactory;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Override;

/**
 * @template T
 */
#[InstanceLocked]
final class FactoryManager implements FactoryManagerInterface
{
    /**
     * @var array<string, object>
     */
    private array $instances = [];

    public function __construct(
        private array $factoryClasses = [],
        private readonly FactoryInterface $defaultFactory = new AutowireFactory(new DependencyResolver()),
    ) {
        $this->instances[self::class] = $this;
    }

    #[Override]
    public function register(array $factoryClasses): void
    {
        $this->factoryClasses = array_merge_recursive($this->factoryClasses, $factoryClasses);
    }

    #[Override]
    public function bind(string $name, object $instance): void
    {
        $this->instances[$name] = $instance;
    }

    public function flush(): void
    {
        $flushable = array_filter(
            $this->instances,
            fn($instance) => !has_attribute($instance, InstanceLocked::class),
        );

        foreach ($flushable as $key => $_) {
            unset($this->instances[$key]);
        }
    }

    #[Override]
    public function produce(string $class): object
    {
        $implementedBy = class_attribute($class, ImplementedBy::class);
        if ($implementedBy instanceof ImplementedBy) {
            return $this->produce($implementedBy->class);
        }

        return $this->createFactory(
            $this->resolveFactory($class),
        )->produce($class, $this);
    }

    #[Override]
    public function get(string $class): object
    {
        $implementedBy = class_attribute($class, ImplementedBy::class);
        if ($implementedBy instanceof ImplementedBy) {
            return $this->get($implementedBy->class);
        }

        if (!array_key_exists($class, $this->instances)) {
            $this->instances[$class] = $this->produce($class);
        }

        return $this->instances[$class];
    }

    private function resolveFactory(string $class): string|Closure|null
    {
        if (isset($this->factoryClasses[$class])) {
            return $this->factoryClasses[$class];
        }

        $factoryAttribute = class_attribute($class, ProducedBy::class);
        if (!$factoryAttribute) {
            return null;
        }

        return $factoryAttribute->factoryClass;
    }

    private function createFactory(string|Closure|null $resolvedFactory): FactoryInterface
    {
        if ($resolvedFactory === null) {
            return $this->defaultFactory;
        }

        if ($resolvedFactory instanceof Closure) {
            return new readonly class ($resolvedFactory) implements FactoryInterface {
                public function __construct(private Closure $factoryClosure) {}

                public function produce(string $class, FactoryProducerInterface $factoryProducer): object
                {
                    return ($this->factoryClosure)($factoryProducer);
                }
            };
        }

        if (!is_subclass_of($resolvedFactory, FactoryInterface::class)) {
            return $this->defaultFactory;
        }

        return new $resolvedFactory();
    }
}
