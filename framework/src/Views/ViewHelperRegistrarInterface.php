<?php

declare(strict_types=1);

namespace Framework\Views;

use Framework\Factories\Attributes\ImplementedBy;
use Framework\Views\ViewHelpers\ViewHelperInterface;

#[ImplementedBy(ViewGenerator::class)]
interface ViewHelperRegistrarInterface
{
    /**
     * @param array<ViewHelperInterface> $viewHelpers
     */
    public function register(array $viewHelpers): void;
}
