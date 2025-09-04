<?php

declare(strict_types=1);

namespace Framework\Consoles\Commands;

use Framework\Consoles\Outputs\ConsoleOutputInterface;

interface CommandInterface
{
    public function run(array $argv, ConsoleOutputInterface $output): void;
}
