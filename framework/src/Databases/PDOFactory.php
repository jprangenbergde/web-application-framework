<?php

declare(strict_types=1);

namespace Framework\Databases;

use Framework\Configurations\ConfigurationRepositoryInterface;
use Framework\Factories\FactoryInterface;
use Framework\FactoryManagers\FactoryProducerInterface;
use PDO;
use RuntimeException;
use Override;

final readonly class PDOFactory implements FactoryInterface
{
    #[Override]
    public function produce(string $class, FactoryProducerInterface $factoryProducer): object
    {
        $configuration = $factoryProducer->get(ConfigurationRepositoryInterface::class);

        $databasePdo = (array) $configuration->get('database.pdo');
        if (!$databasePdo) {
            throw new RuntimeException('Configuration `database.pdo` is not defined');
        }

        $dsn = $databasePdo['dsn'] ?? null;
        if (!is_string($dsn)) {
            throw new RuntimeException('Configuration `database.pdo.dsn` must be a string');
        }

        $username = $databasePdo['username'] ?? null;
        $password = $databasePdo['password'] ?? null;
        $options = $databasePdo['options'] ?? [];

        return new PDO(
            $dsn,
            is_string($username) ? $username : null,
            is_string($password) ? $password : null,
            (is_array($options) ? $options : []) + [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
        );
    }
}
