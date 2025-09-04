<?php

declare(strict_types=1);

use Framework\Configurations\ConfigurationInterface;
use Framework\Interfaces\ArrayableInterface;

/**
 * @template T
 *
 * @param class-string<T> $className
 *
 * @return T
 * @see Flightweight-Pattern
 */
function instance(string $className): object
{
    /** @var \Framework\Application\ApplicationInterface $application */
    global $application;

    return $application->producer()->get($className);
}

function config(): ConfigurationInterface
{
    return instance(ConfigurationInterface::class);
}

function env(string $variable, mixed $default = null): ?string
{
    return $_ENV[$variable] ?? $default;
}

/**
 * @template T of object
 * @param class-string<T>|T $attribute
 * @return T|null
 */
function class_attribute(string|object $classOrObject, string $attribute): ?object
{
    try {
        $reflectionClass = new ReflectionClass($classOrObject);
    } catch (ReflectionException) {
        return null;
    }

    $attributes = $reflectionClass->getAttributes($attribute);
    if ($attributes === []) {
        return null;
    }

    return $attributes[0]->newInstance();
}

function has_attribute(string|object $classOrObject, string $attribute): bool
{
    try {
        $reflectionClass = new ReflectionClass($classOrObject);
    } catch (ReflectionException) {
        return false;
    }

    return $reflectionClass->getAttributes($attribute) !== [];
}

/**
 * @template T of object
 * @param class-string<T>|T $attribute
 * @return T
 */
function method_attribute(string|object $classOrObject, string $method, string $attribute): ?object
{
    try {
        $reflectionClass = new ReflectionClass($classOrObject);
    } catch (ReflectionException) {
        return null;
    }

    if (!$reflectionClass->hasMethod($method)) {
        throw new RuntimeException(
            sprintf(
                "Method %s does not exist in class %s",
                $method,
                is_object($classOrObject) ? $classOrObject::class : $classOrObject
            )
        );
    }

    $attributes = $reflectionClass->getMethod($method)->getAttributes($attribute);
    if ($attributes === []) {
        return null;
    }

    return $attributes[0]->newInstance();
}

function resolve_namespace(string $filePath): ?string
{
    $content = file_get_contents($filePath);
    preg_match('/(namespace)(.*);/i', $content, $matches);

    return trim((string) ($matches[2] ?? null));
}

/**
 * @deprecated
 */
function array_column_of_list_or_map(array $filters, string $columnKey): array
{
    if (!array_is_list($filters)) {
        return array_column($filters, $columnKey);
    }

    $columns = [];

    $filterGroup = $filters;
    foreach ($filterGroup as $filtersList) {
        $columns = array_merge($columns, array_column_of_list_or_map($filtersList, $columnKey));
    }

    return $columns;
}

/**
 * @param array<ArrayableInterface> $objects
 */
function objects_to_maps(array $objects): array
{
    $maps = [];

    foreach ($objects as $object) {
        if (!$object instanceof ArrayableInterface) {
            continue;
        }

        $maps[] = $object->toArray();
    }

    return $maps;
}

/**
 * @param class-string<ArrayableInterface> $class
 */
function maps_to_objects(array $maps, string $class): array
{
    $objects = [];

    foreach ($maps as $map) {
        if (!is_subclass_of($class, ArrayableInterface::class)) {
            continue;
        }

        $objects[] = $class::fromArray($map);
    }

    return $objects;
}
