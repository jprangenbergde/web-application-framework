<?php

declare(strict_types=1);

namespace Framework\Providers\Observers;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Listeners\ObserverInterface;

#[ImplementedBy(ObserverProvider::class)]
interface ObserverProviderInterface
{
    /**
     * @return array<ObserverInterface>
     */
    public function provide(): array;
}
