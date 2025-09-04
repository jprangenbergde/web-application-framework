<?php

declare(strict_types=1);

namespace Framework\Consoles\Outputs;

use Override;

final readonly class NullConsoleOutput implements ConsoleOutputInterface
{
    #[Override]
    public function line(mixed $data): void {}

    #[Override]
    public function newLine(): void {}

    #[Override]
    public function success(mixed $data): void {}

    #[Override]
    public function warning(mixed $data): void {}

    #[Override]
    public function info(mixed $data): void {}

    #[Override]
    public function error(mixed $data): void {}
}
