<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;

final readonly class ApplicationBootStarted extends Event implements LifecycleEventInterface {}
