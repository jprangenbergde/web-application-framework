<?php

declare(strict_types=1);

namespace Tests;

use Framework\Databases\Transactions\TransactionManager;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class DatabaseTestCase extends TestCase
{
    protected PDO $pdo;

    protected TransactionManager $transactions;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:', options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $this->transactions = new TransactionManager($this->pdo);

        foreach (glob(__DIR__ . '/../framework/database/schema/*.sql') ?: [] as $schema) {
            $this->pdo->exec((string) file_get_contents($schema));
        }

        $this->pdo->exec('CREATE TABLE items (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)');
    }

    protected function itemCount(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) FROM items')->fetchColumn();
    }

    protected function addItem(string $name): void
    {
        $this->pdo->prepare('INSERT INTO items (name) VALUES (?)')->execute([$name]);
    }
}
