<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Events\Registered;
use App\Models\User;
use App\Services\User\UserService;

final readonly class RegisterService
{
    public function __construct(
        private UserService $userService
    ) {}

    public function register(string $firstName, string $lastName, string $email, string $password): void
    {
        $user = $this->userService->findByEmail($email);

        if ($user !== null) {
            return;
        }

        $user = User::createWithPlainTextPassword(
            $firstName,
            $lastName,
            $email,
            $password
        );

        $this->userService->create($user);

        Registered::queue($user->email, $user->firstName, $user->lastName);
    }
}
