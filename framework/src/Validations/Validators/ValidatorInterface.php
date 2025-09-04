<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\ValidationInterface;

interface ValidatorInterface
{
    public function validate(mixed $data): ValidationInterface;
}
