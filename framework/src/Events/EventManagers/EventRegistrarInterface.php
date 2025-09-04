<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Listeners\ObserverInterface;

#[ImplementedBy(EventManager::class)]
interface EventRegistrarInterface
{
    /**
     * @param array<ObserverInterface> $observers
     */
    public function register(array $observers): void;
}
