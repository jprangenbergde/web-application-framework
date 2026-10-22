<?php

declare(strict_types=1);

namespace Framework\Databases\Transactions;

use LogicException;
use Override;
use PDO;
use Throwable;

final class TransactionManager implements TransactionManagerInterface
{
    /**
     * Callbacks je Transaktionsebene (Index 0 = äußerste Transaktion).
     *
     * @var array<int, array<callable>>
     */
    private array $afterCommitCallbacks = [];

    private int $depth = 0;

    public function __construct(private readonly PDO $connection) {}

    #[Override]
    public function transactional(callable $callback): mixed
    {
        $this->begin();

        try {
            $result = $callback();
        } catch (Throwable $throwable) {
            $this->rollBack();

            throw $throwable;
        }

        $this->commit();

        return $result;
    }

    #[Override]
    public function begin(): void
    {
        if ($this->depth === 0) {
            $this->connection->beginTransaction();
        } else {
            $this->connection->exec('SAVEPOINT ' . $this->savepoint($this->depth));
        }

        $this->afterCommitCallbacks[$this->depth] = [];
        ++$this->depth;
    }

    #[Override]
    public function commit(): void
    {
        $this->assertInTransaction();

        --$this->depth;

        if ($this->depth > 0) {
            $this->connection->exec('RELEASE SAVEPOINT ' . $this->savepoint($this->depth));

            // Callbacks wandern in die umschließende Ebene und feuern erst beim äußersten Commit.
            $this->afterCommitCallbacks[$this->depth - 1] = [
                ...$this->afterCommitCallbacks[$this->depth - 1],
                ...$this->afterCommitCallbacks[$this->depth],
            ];
            unset($this->afterCommitCallbacks[$this->depth]);

            return;
        }

        $callbacks = $this->afterCommitCallbacks[0];
        $this->afterCommitCallbacks = [];

        $this->connection->commit();

        foreach ($callbacks as $callback) {
            $callback();
        }
    }

    #[Override]
    public function rollBack(): void
    {
        $this->assertInTransaction();

        --$this->depth;
        unset($this->afterCommitCallbacks[$this->depth]);

        if ($this->depth > 0) {
            $savepoint = $this->savepoint($this->depth);
            $this->connection->exec('ROLLBACK TO SAVEPOINT ' . $savepoint);
            $this->connection->exec('RELEASE SAVEPOINT ' . $savepoint);

            return;
        }

        $this->connection->rollBack();
    }

    #[Override]
    public function inTransaction(): bool
    {
        return $this->depth > 0;
    }

    #[Override]
    public function afterCommit(callable $callback): void
    {
        if ($this->depth === 0) {
            $callback();

            return;
        }

        $this->afterCommitCallbacks[$this->depth - 1][] = $callback;
    }

    private function savepoint(int $level): string
    {
        return 'tx_' . $level;
    }

    private function assertInTransaction(): void
    {
        if ($this->depth === 0) {
            throw new LogicException('There is no active transaction.');
        }
    }
}
