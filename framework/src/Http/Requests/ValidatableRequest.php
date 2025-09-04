<?php

declare(strict_types=1);

namespace Framework\Http\Requests;

use Framework\Validations\ValidatableInterface;
use Framework\Validations\Validation;
use Framework\Validations\Validators\ValidatorInterface;
use Override;

abstract readonly class ValidatableRequest extends DecoratedRequest implements ValidatableInterface
{
    /**
     * @return class-string<ValidatorInterface>
     */
    abstract protected function validatorClass(): string;

    #[Override]
    public function validate(): Validation
    {
        return instance($this->validatorClass())->validate($this->request->getPayload());
    }
}
