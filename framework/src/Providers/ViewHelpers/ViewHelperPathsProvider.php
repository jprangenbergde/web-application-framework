<?php

declare(strict_types=1);

namespace Framework\Providers\ViewHelpers;

use Framework\Factories\Attributes\Config;
use Framework\Providers\PathsProvider;
use Framework\Views\ViewHelpers\ViewHelperInterface;
use Override;

final readonly class ViewHelperPathsProvider extends PathsProvider implements ViewHelperProviderInterface
{
    public function __construct(
        #[Config('view.viewHelpers.paths', [])]
        array $paths
    ) {
        parent::__construct($paths);
    }

    #[Override]
    public function provide(): array
    {
        if (PHP_SAPI === "cli") {
            return [];
        }

        return $this->fromPathsRecursively(function (string $class) {
            if (!is_subclass_of($class, ViewHelperInterface::class)) {
                return null;
            }

            return instance($class);
        });
    }
}
