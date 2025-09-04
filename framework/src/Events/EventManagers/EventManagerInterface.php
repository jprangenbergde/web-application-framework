<?php

declare(strict_types=1);

namespace Framework\Events\EventManagers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(EventManager::class)]
interface EventManagerInterface extends
    EventAttacherInterface,
    EventDetacherInterface,
    EventNotifierInterface,
    EventRegistrarInterface {}
