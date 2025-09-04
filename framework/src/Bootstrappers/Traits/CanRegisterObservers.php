<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\Events\EventManagers\EventRegistrarInterface;
use Framework\Listeners\ObserverInterface;

trait CanRegisterObservers
{
    /**
     * @param array<ObserverInterface> $observers
     */
    private function registerObservers(array $observers): void
    {
        $this->factoryProducer
            ->get(EventRegistrarInterface::class)
            ->register($observers);
    }
}
