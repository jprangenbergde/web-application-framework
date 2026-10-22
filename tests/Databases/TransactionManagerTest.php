<?php

declare(strict_types=1);

namespace Tests\Databases;

use LogicException;
use RuntimeException;
use Tests\DatabaseTestCase;

final class TransactionManagerTest extends DatabaseTestCase
{
    public function testCommitPersistsAndReturnsCallbackResult(): void
    {
        $result = $this->transactions->transactional(function (): string {
            $this->addItem('a');

            return 'done';
        });

        self::assertSame('done', $result);
        self::assertSame(1, $this->itemCount());
        self::assertFalse($this->transactions->inTransaction());
    }

    public function testExceptionRollsBackAndIsRethrown(): void
    {
        try {
            $this->transactions->transactional(function (): void {
                $this->addItem('a');

                throw new RuntimeException('boom');
            });
            self::fail('Exception expected');
        } catch (RuntimeException $exception) {
            self::assertSame('boom', $exception->getMessage());
        }

        self::assertSame(0, $this->itemCount());
        self::assertFalse($this->transactions->inTransaction());
    }

    public function testInnerRollbackKeepsOuterTransactionIntact(): void
    {
        $this->transactions->transactional(function (): void {
            $this->addItem('outer');

            try {
                $this->transactions->transactional(function (): void {
                    $this->addItem('inner');

                    throw new RuntimeException('inner failed');
                });
            } catch (RuntimeException) {
            }

            self::assertTrue($this->transactions->inTransaction());
        });

        self::assertSame(1, $this->itemCount());
        self::assertSame('outer', $this->pdo->query('SELECT name FROM items')->fetchColumn());
    }

    public function testInnerCommitIsRolledBackWithOuterTransaction(): void
    {
        try {
            $this->transactions->transactional(function (): void {
                $this->transactions->transactional(fn() => $this->addItem('inner'));

                throw new RuntimeException('outer failed');
            });
        } catch (RuntimeException) {
        }

        self::assertSame(0, $this->itemCount());
    }

    public function testAfterCommitRunsAfterOutermostCommit(): void
    {
        $calls = [];

        $this->transactions->transactional(function () use (&$calls): void {
            $this->transactions->transactional(function () use (&$calls): void {
                $this->transactions->afterCommit(function () use (&$calls): void {
                    $calls[] = 'after';
                });
            });

            $calls[] = 'body-end';
        });

        self::assertSame(['body-end', 'after'], $calls);
    }

    public function testAfterCommitIsDiscardedOnRollback(): void
    {
        $called = false;

        try {
            $this->transactions->transactional(function () use (&$called): void {
                $this->transactions->afterCommit(function () use (&$called): void {
                    $called = true;
                });

                throw new RuntimeException('boom');
            });
        } catch (RuntimeException) {
        }

        self::assertFalse($called);
    }

    public function testAfterCommitFromRolledBackInnerLevelIsDiscarded(): void
    {
        $calls = [];

        $this->transactions->transactional(function () use (&$calls): void {
            try {
                $this->transactions->transactional(function () use (&$calls): void {
                    $this->transactions->afterCommit(function () use (&$calls): void {
                        $calls[] = 'inner';
                    });

                    throw new RuntimeException('inner failed');
                });
            } catch (RuntimeException) {
            }

            $this->transactions->afterCommit(function () use (&$calls): void {
                $calls[] = 'outer';
            });
        });

        self::assertSame(['outer'], $calls);
    }

    public function testAfterCommitRunsImmediatelyWithoutTransaction(): void
    {
        $called = false;
        $this->transactions->afterCommit(function () use (&$called): void {
            $called = true;
        });

        self::assertTrue($called);
    }

    public function testCommitWithoutTransactionThrows(): void
    {
        $this->expectException(LogicException::class);

        $this->transactions->commit();
    }
}
