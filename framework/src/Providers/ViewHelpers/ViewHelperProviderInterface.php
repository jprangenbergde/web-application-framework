<?php

declare(strict_types=1);

namespace Framework\Providers\ViewHelpers;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Views\ViewHelpers\ViewHelperInterface;

#[ImplementedBy(ViewHelperPathsProvider::class)]
interface ViewHelperProviderInterface
{
    /**
     * @return array<ViewHelperInterface>
     */
    public function provide(): array;
}
