<?php

declare(strict_types=1);

namespace Framework\Http\Routers;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Router::class)]
interface RouterInterface extends RouteAssemblerInterface, RouteRegistrarInterface, RouteMatcherInterface {}
