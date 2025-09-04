<?php

declare(strict_types=1);

namespace Framework\Listeners;

use Framework\Events\EventManagers\EventAttacherInterface;

interface ObserverInterface
{
    public function observe(EventAttacherInterface $attacher): void;
}
