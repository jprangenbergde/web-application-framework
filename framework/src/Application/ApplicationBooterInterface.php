<?php

declare(strict_types=1);

namespace Framework\Application;

interface ApplicationBooterInterface
{
    public function boot(): ApplicationRunnerInterface;
}
