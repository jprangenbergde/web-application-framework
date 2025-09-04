<?php

declare(strict_types=1);

namespace Framework\Events;

use Framework\Events\Traits\CanOccur;

abstract readonly class Event implements ObservableInterface
{
    use CanOccur;
}
