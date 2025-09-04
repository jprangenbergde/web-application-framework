<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Models\ModelInterface;
use Override;

final class User implements ModelInterface
{
    public function __construct(
        public int $id,
        public string $firstName,
        public string $lastName,
        public string $email,
        private string $password = '',
    ) {}

    #[Override]
    public function getId(): int
    {
        return $this->id;
    }

    #[Override]
    public static function getIdColumnName(): string
    {
        return 'id';
    }

    #[Override]
    public static function getTableName(): string
    {
        return 'users';
    }

    #[Override]
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            (string) $data['firstName'],
            (string) $data['lastName'],
            (string) $data['email'],
            (string) $data['password']
        );
    }

    public static function createWithPlainTextPassword(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): self {
        return new self(
            0,
            $firstName,
            $lastName,
            $email,
            self::hashPassword($password),
        );
    }

    private static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function setPassword(string $password): void
    {
        $this->password = self::hashPassword($password);
    }

    public function isPasswordValid(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
