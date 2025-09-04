<?php

declare(strict_types=1);

namespace Framework\Dependencies;

use ReflectionClass;
use ReflectionParameter;
use RuntimeException;

final readonly class DependencyResolver
{
    /**
     * @return array<Dependency>
     */
    public function resolveConstructorDependencies(string $class): array
    {
        $reflection = new ReflectionClass($class);

        $dependencies = [];
        foreach ($reflection->getConstructor()?->getParameters() ?? [] as $parameter) {
            $this->addDependency($parameter, $dependencies);
        }

        return $dependencies;
    }

    /**
     * @return array<Dependency>
     */
    public function resolveMethodDependencies(string $class, string $method): array
    {
        $reflectionClass = new ReflectionClass($class);

        if (!$reflectionClass->hasMethod($method)) {
            throw new RuntimeException(
                sprintf(
                    "Method %s does not exist in class %s",
                    $method,
                    $class
                )
            );
        }

        $dependencies = [];
        foreach ($reflectionClass->getMethod($method)->getParameters() as $parameter) {
            $this->addDependency($parameter, $dependencies);
        }

        return $dependencies;
    }

    /**
     * @param array<Dependency> $dependencies
     */
    private function addDependency(ReflectionParameter $parameter, array &$dependencies): void
    {
        $typeName = $parameter->getType()?->getName();
        if (!$typeName) {
            throw new RuntimeException(
                sprintf(
                    'Dependency resolved without type for parameter %s',
                    $parameter->getName()
                )
            );
        }

        $dependencies[] = new Dependency(
            $parameter->getName(),
            $typeName,
            $parameter->getAttributes()
        );
    }
}
