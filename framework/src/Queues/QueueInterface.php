<?php

namespace Framework\Queues;

use Framework\Factories\Attributes\ProducedBy;

#[ProducedBy(QueueFactory::class)]
interface QueueInterface
{
    public function append(QueueableInterface $queueable): void;

    public function pop(): ?QueueableInterface;
}
