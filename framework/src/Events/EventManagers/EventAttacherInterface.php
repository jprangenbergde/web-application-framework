<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Events\ObservableInterface;
use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(EventManager::class)]
interface EventAttacherInterface
{
    /**
     * @param class-string<ObservableInterface> $observableClass
     */
    public function attach(string $observableClass, callable $observer, int $priority = 0): void;
}
