<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Validators\Auth\RegisterValidator;
use Framework\Http\Requests\ValidatableRequest;
use Override;

final readonly class RegisterRequest extends ValidatableRequest
{
    #[Override]
    protected function validatorClass(): string
    {
        return RegisterValidator::class;
    }

    public function firstName(): string
    {
        return (string) $this->getPayload()->get('firstName');
    }

    public function lastName(): string
    {
        return (string) $this->getPayload()->get('lastName');
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
