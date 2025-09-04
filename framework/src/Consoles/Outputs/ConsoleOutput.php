<?php

declare(strict_types=1);

namespace Framework\Consoles\Outputs;

use Override;

final readonly class ConsoleOutput implements ConsoleOutputInterface
{
    #[Override]
    public function line(mixed $data): void
    {
        echo $this->transform($data) . PHP_EOL;
    }

    #[Override]
    public function newLine(): void
    {
        echo PHP_EOL;
    }

    #[Override]
    public function success(mixed $data): void
    {
        echo "\e[0;32;40m{$this->transform($data)}\e[0m\n";
    }

    #[Override]
    public function warning(mixed $data): void
    {
        echo "\e[1;33;40m{$this->transform($data)}\e[0m\n";
    }

    #[Override]
    public function info(mixed $data): void
    {
        echo "\e[0;36;40m{$this->transform($data)}\e[0m\n";
    }

    #[Override]
    public function error(mixed $data): void
    {
        echo "\e[0;31;40m{$this->transform($data)}\e[0m\n";
    }

    private function transform(mixed $data): string
    {
        if (is_string($data)) {
            return $data;
        } elseif (is_array($data)) {
            return json_encode($data);
        }

        return $data;
    }
}
