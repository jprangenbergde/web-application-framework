<?php

declare(strict_types=1);

namespace Framework\Providers\Commands;

use Framework\Consoles\Commands\CommandInterface;
use Framework\Factories\Attributes\Config;
use Framework\Providers\PathsProvider;
use Override;

final readonly class CommandProvider extends PathsProvider implements CommandProviderInterface
{
    public function __construct(
        #[Config('console.commands.paths', [])]
        array $paths
    ) {
        parent::__construct($paths);
    }

    #[Override]
    public function provide(): array
    {
        return $this->fromPathsRecursively(function (string $class) {
            if (!is_subclass_of($class, CommandInterface::class)) {
                return null;
            }

            return $class;
        });
    }
}
