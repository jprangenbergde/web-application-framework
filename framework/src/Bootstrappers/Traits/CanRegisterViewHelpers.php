<?php

declare(strict_types=1);

namespace Framework\Bootstrappers\Traits;

use Framework\Views\ViewHelperRegistrarInterface;
use Framework\Views\ViewHelpers\ViewHelperInterface;

trait CanRegisterViewHelpers
{
    /**
     * @param array<ViewHelperInterface> $viewHelpers
     */
    private function registerViewHelpers(array $viewHelpers): void
    {
        $this->factoryProducer
            ->get(ViewHelperRegistrarInterface::class)
            ->register($viewHelpers);
    }
}
