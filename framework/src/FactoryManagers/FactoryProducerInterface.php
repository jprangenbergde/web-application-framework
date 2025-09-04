<?php

declare(strict_types=1);

namespace Framework\FactoryManagers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(FactoryManager::class)]
interface FactoryProducerInterface
{
    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    public function produce(string $class): object;

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     *
     * @return T
     * @see Flightweight-Pattern
     */
    public function get(string $class): object;
}
