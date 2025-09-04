<?php

declare(strict_types=1);

namespace Framework\Events\Traits;

use Framework\Events\EventManagers\EventNotifierInterface;
use Framework\Events\ObservableInterface;

trait CanOccur
{
    public static function occur(mixed ...$parameters): void
    {
        if (count($parameters) === 1 && $parameters[0] instanceof ObservableInterface) {
            instance(EventNotifierInterface::class)->notify(...$parameters);
        } else {
            instance(EventNotifierInterface::class)->notify(new static(...$parameters));
        }
    }
}
