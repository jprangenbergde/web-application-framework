<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;

final readonly class RunFinished extends Event implements LifecycleEventInterface {}
