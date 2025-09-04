<?php

declare(strict_types=1);

namespace Framework\Providers;

use Closure;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;

abstract readonly class PathsProvider
{
    public function __construct(
        private array $paths,
    ) {}

    protected function fromPathsRecursively(Closure $provider): array
    {
        $provided = [];

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            $directory = new RecursiveDirectoryIterator($path);
            $iterator = new RecursiveIteratorIterator($directory);
            $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

            foreach ($regex as $currentDirectory) {
                $class = sprintf(
                    '%s\\%s',
                    resolve_namespace($currentDirectory[0]),
                    basename((string) $currentDirectory[0], '.php')
                );

                $provided[] = $provider($class);
            }
        }

        return array_values(array_filter($provided));
    }
}
