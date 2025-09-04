<?php

declare(strict_types=1);

namespace Framework\Dependencies;

use ReflectionAttribute;

final readonly class Dependency
{
    /**
     * @param class-string $type
     * @param array<ReflectionAttribute> $attributes
     */
    public function __construct(
        public string $name,
        public string $type,
        public array $attributes
    ) {}

    public function exists(): bool
    {
        return class_exists($this->type) || interface_exists($this->type);
    }

    public function hasAttribute(string $class): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $class) {
                return true;
            }
        }

        return false;
    }

    public function getAttribute(string $class): ?object
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute->getName() === $class) {
                return $attribute->newInstance();
            }
        }

        return null;
    }
}
