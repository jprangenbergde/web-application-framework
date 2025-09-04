<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use Override;

final readonly class EmailValidator implements ValidatorInterface
{
    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        if (!is_string($data)) {
            return Validation::errors([
                ':value is not a string',
            ]);
        }

        if (!str_contains($data, '@')) {
            return Validation::errors([
                ':value is not a valid email address',
            ]);
        }

        return Validation::ok();
    }
}
