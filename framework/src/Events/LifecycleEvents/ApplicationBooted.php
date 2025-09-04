<?php

declare(strict_types=1);

namespace Framework\Events\LifecycleEvents;

use Framework\Events\Event;

final readonly class ApplicationBooted extends Event implements LifecycleEventInterface {}
