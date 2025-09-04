<?php

declare(strict_types=1);

namespace App\Validators\Auth;

use Framework\Validations\Validators\EmailValidator;
use Framework\Validations\Validators\FilledValidator;
use Framework\Validations\Validators\MapValidator;
use Framework\Validations\Validators\ValidatorChain;

final readonly class RegisterValidator extends MapValidator
{
    public function __construct(
        EmailValidator $emailValidator,
        FilledValidator $notEmptyValidator,
    ) {
        parent::__construct([
            'firstName' => $notEmptyValidator,
            'lastName' => $notEmptyValidator,
            'email' => new ValidatorChain([
                $notEmptyValidator,
                $emailValidator,
            ]),
            'password' => $notEmptyValidator,
        ]);
    }
}
