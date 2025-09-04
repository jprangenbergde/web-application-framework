<?php

declare(strict_types=1);

namespace Framework\Application;

interface ApplicationHandlerInterface
{
    public function handle(): void;
}
