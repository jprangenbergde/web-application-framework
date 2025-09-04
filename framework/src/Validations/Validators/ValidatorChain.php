<?php

declare(strict_types=1);

namespace Framework\Validations\Validators;

use Framework\Validations\Validation;
use Framework\Validations\ValidationInterface;
use Override;

readonly class ValidatorChain implements ValidatorInterface
{
    /**
     * @param array<ValidatorInterface> $validators
     */
    public function __construct(
        private array $validators
    ) {}

    #[Override]
    public function validate(mixed $data): ValidationInterface
    {
        $messages = [];
        foreach ($this->validators as $validator) {
            $validation = $validator->validate($data);
            if ($validation->valid()) {
                continue;
            }

            $messages = array_merge($messages, $validation->messages());
        }

        if ($messages !== []) {
            return Validation::errors(array_unique($messages));
        }

        return Validation::ok();
    }
}
