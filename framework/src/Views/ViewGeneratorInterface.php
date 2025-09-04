<?php

declare(strict_types=1);

namespace Framework\Views;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(ViewGenerator::class)]
interface ViewGeneratorInterface
{
    public function generate(string $view, array $data = []): string;
}
