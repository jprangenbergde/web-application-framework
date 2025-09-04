<?php

declare(strict_types=1);

namespace Framework\Consoles;

use Framework\Factories\Attributes\ImplementedBy;

#[ImplementedBy(Console::class)]
interface ConsoleInterface extends CommandRegistrarInterface, CommandRunnerInterface {}
