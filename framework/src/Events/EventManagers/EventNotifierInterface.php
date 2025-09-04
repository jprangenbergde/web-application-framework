<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Events\ObservableInterface;
use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(EventManager::class)]
interface EventNotifierInterface
{
    public function notify(ObservableInterface $observable): void;
}
