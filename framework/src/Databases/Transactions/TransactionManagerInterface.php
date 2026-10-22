<?php

declare(strict_types=1);

namespace Framework\Databases\Transactions;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(TransactionManager::class)]
interface TransactionManagerInterface
{
    /**
     * Führt den Callback in einer Transaktion aus (verschachtelt über Savepoints).
     * Bei einer Exception wird zurückgerollt und die Exception weitergeworfen.
     *
     * @template T
     *
     * @param callable(): T $callback
     *
     * @return T
     */
    public function transactional(callable $callback): mixed;

    public function begin(): void;

    public function commit(): void;

    public function rollBack(): void;

    public function inTransaction(): bool;

    /**
     * Führt den Callback nach dem äußersten Commit aus. Läuft keine Transaktion, wird er sofort ausgeführt.
     * Wird die Transaktionsebene zurückgerollt, in der er registriert wurde, verfällt er.
     *
     * @param callable(): void $callback
     */
    public function afterCommit(callable $callback): void;
}
