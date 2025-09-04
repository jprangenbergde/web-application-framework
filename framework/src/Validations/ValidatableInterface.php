<?php

declare(strict_types=1);

namespace Framework\Validations;

interface ValidatableInterface
{
    public function validate(): Validation;
}
