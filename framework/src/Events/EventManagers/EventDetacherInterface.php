<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Events\ObservableInterface;
use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(EventManager::class)]
interface EventDetacherInterface
{
    /**
     * @param class-string<ObservableInterface> $observableClass
     */
    public function detach(string $observableClass): void;
}
