<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\User;
use Framework\Databases\DatabaseManager;

final readonly class UserService
{
    public function __construct(
        private DatabaseManager $databaseManager
    ) {}

    public function create(User $user): void
    {
        $this->databaseManager->save($user);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->databaseManager->select(User::class, [
            'email' => ['value' => $email],
        ]);
    }
}
