<?php

declare(strict_types=1);

namespace Framework\Consoles\Outputs;

use Framework\Factories\Attributes\ProducedBy;

#[ProducedBy(ConsoleOutputFactory::class)]
interface ConsoleOutputInterface
{
    public function line(mixed $data): void;

    public function newLine(): void;

    public function success(mixed $data): void;

    public function warning(mixed $data): void;

    public function info(mixed $data): void;

    public function error(mixed $data): void;
}
