<?php

declare(strict_types=1);

namespace Framework\FactoryManagers\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class InstanceLocked {}
