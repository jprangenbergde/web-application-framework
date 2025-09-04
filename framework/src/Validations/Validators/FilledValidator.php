<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use Override;

final readonly class FilledValidator implements ValidatorInterface
{
    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        if (!is_string($data)) {
            return Validation::errors([
                ':value is not a string',
            ]);
        }

        if ($data === '') {
            return Validation::errors([
                ':value is not filled',
            ]);
        }

        return Validation::ok();
    }
}
