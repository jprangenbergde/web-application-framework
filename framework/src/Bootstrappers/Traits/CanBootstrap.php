<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

trait CanBootstrap
{
    public static function bootstrap(): void
    {
        instance(static::class)->boot();
    }
}
