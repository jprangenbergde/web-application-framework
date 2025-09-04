<?php

declare(strict_types=1);

namespace Framework\Providers\Routes;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Http\Routers\Route;

#[ImplementedBy(RouteProvider::class)]
interface RouteProviderInterface
{
    /**
     * @return array<Route>
     */
    public function provide(): array;
}
