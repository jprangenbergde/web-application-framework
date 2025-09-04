<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use Override;

final readonly class ExistsValidator implements ValidatorInterface
{
    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        if ($data === null) {
            return Validation::errors([
                ':value does not exists',
            ]);
        }

        return Validation::ok();
    }
}
