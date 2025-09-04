<?php

declare(strict_types=1);

namespace Framework\Jobs;

use BadMethodCallException;
use Framework\Dependencies\Dependency;
use Framework\Dependencies\DependencyResolver;
use Framework\Queues\QueueableInterface;
use Framework\Queues\QueueInterface;

/**
 * @see Command-Pattern
 */
final readonly class JobManager
{
    public function __construct(
        private DependencyResolver $dependencyResolver,
        private QueueInterface $queue,
    ) {}

    public function queue(QueueableInterface $queueable): void
    {
        $this->queue->append($queueable);
    }

    public function dequeue(): void
    {
        while ($queueable = $this->queue->pop()) {
            if (!method_exists($queueable, 'execute')) {
                throw new BadMethodCallException(
                    sprintf(
                        'Method execute() does not exist in class %s',
                        $queueable::class
                    )
                );
            }

            $queueable->execute(
                ...array_map(
                    static fn(Dependency $dependency) => instance($dependency->type),
                    $this->dependencyResolver->resolveMethodDependencies($queueable::class, 'execute')
                )
            );
        }
    }
}
