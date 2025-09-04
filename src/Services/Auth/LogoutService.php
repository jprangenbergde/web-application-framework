<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Framework\Http\Requests\Request\SessionInterface;

final readonly class LogoutService
{
    public function logout(SessionInterface $session): void
    {
        if (!$session->has('user')) {
            return;
        }

        $session->unset('user');
    }
}
