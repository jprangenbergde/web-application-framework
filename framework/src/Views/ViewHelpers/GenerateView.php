<?php

declare(strict_types=1);

namespace Framework\Views\ViewHelpers;

use Framework\Views\ViewGeneratorInterface;
use Override;

final readonly class GenerateView implements ViewHelperInterface
{
    public function __construct(
        private ViewGeneratorInterface $viewGenerator
    ) {}

    #[Override]
    public function getName(): string
    {
        return 'generateView';
    }

    public function __invoke(string $view, array $data = []): string
    {
        return $this->viewGenerator->generate($view, $data);
    }
}
