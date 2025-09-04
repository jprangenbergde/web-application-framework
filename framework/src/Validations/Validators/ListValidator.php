<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use Override;

readonly class ListValidator implements ValidatorInterface
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        $messages = [];

        foreach ($data as $value) {
            $validation = $this->validator->validate($value);
            if ($validation->valid()) {
                continue;
            }

            $messages = array_merge($messages, $validation->messages());
        }

        if ($messages !== []) {
            return Validation::errors($messages);
        }

        return Validation::ok();
    }
}
