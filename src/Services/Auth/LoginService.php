<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Services\User\UserService;
use Framework\Http\Requests\Request\SessionInterface;

final readonly class LoginService
{
    public function __construct(
        private UserService $userService,
    ) {}

    public function login(string $email, string $password, SessionInterface $session): bool
    {
        $user = $this->userService->findByEmail($email);

        if ($user !== null && $user->isPasswordValid($password)) {
            $session->set(
                (string) config()->get('auth.sessionKey', 'user'),
                $email
            );

            return true;
        }

        return false;
    }
}
