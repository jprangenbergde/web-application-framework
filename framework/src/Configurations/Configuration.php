<?php

declare(strict_types=1);

namespace Framework\Configurations;

use Framework\Factories\Attributes\ProducedBy;
use Framework\FactoryManagers\Attributes\InstanceLocked;
use Throwable;

use Override;

use function Flow\ArrayDot\array_dot_get;

#[InstanceLocked]
#[ProducedBy(ConfigurationFactory::class)]
final class Configuration implements ConfigurationInterface
{
    public function __construct(
        private array $configurations = []
    ) {}

    #[Override]
    public function register(array $configurations): void
    {
        $this->configurations = array_merge_recursive($this->configurations, $configurations);
    }

    #[Override]
    public function get(string $key, mixed $default = null): mixed
    {
        if (isset($this->configurations[$key])) {
            return $this->configurations[$key];
        }

        try {
            return array_dot_get(
                $this->configurations,
                $key
            );
        } catch (Throwable) {
            return $default;
        }
    }

    public function set(string $key, mixed $value): void
    {
        $this->configurations[$key] = $value;
    }
}
