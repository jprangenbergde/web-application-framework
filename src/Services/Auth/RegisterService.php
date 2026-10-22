<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Events\Registered;
use App\Models\User;
use App\Services\User\UserService;
use Framework\Databases\Transactions\TransactionManagerInterface;

final readonly class RegisterService
{
    public function __construct(
        private UserService $userService,
        private TransactionManagerInterface $transactionManager,
    ) {}

    public function register(string $firstName, string $lastName, string $email, string $password): void
    {
        $this->transactionManager->transactional(function () use ($firstName, $lastName, $email, $password): void {
            if ($this->userService->findByEmail($email) !== null) {
                return;
            }

            $user = User::createWithPlainTextPassword(
                $firstName,
                $lastName,
                $email,
                $password
            );

            $this->userService->create($user);

            // Landet über die Outbox atomar zusammen mit dem User in der Datenbank.
            Registered::queue($user->email, $user->firstName, $user->lastName);
        });
    }
}
