<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Validators\Auth\LoginValidator;
use Framework\Http\Requests\ValidatableRequest;
use Override;

final readonly class LoginRequest extends ValidatableRequest
{
    #[Override]
    protected function validatorClass(): string
    {
        return LoginValidator::class;
    }

    public function email(): string
    {
        return (string) $this->getPayload()->get('email');
    }

    public function password(): string
    {
        return (string) $this->getPayload()->get('password');
    }
}
