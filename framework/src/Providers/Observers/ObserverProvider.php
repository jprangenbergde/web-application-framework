<?php

declare(strict_types=1);

namespace Framework\Providers\Observers;

use Framework\Factories\Attributes\Config;
use Framework\Listeners\ObserverInterface;
use Framework\Providers\PathsProvider;
use Override;

final readonly class ObserverProvider extends PathsProvider implements ObserverProviderInterface
{
    public function __construct(
        #[Config('event.listeners.paths', [])]
        array $paths
    ) {
        parent::__construct($paths);
    }

    #[Override]
    public function provide(): array
    {
        return $this->fromPathsRecursively(static function (string $class) {
            if (!is_subclass_of($class, ObserverInterface::class)) {
                return null;
            }

            return instance($class);
        });
    }
}
