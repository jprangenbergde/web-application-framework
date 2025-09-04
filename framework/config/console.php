<?php

declare(strict_types=1);

use Framework\Consoles\Outputs\ConsoleOutput;

return [
    'commands' => [
        'paths' => [
            __DIR__ . '/../src/Consoles/Commands',
        ],
    ],
    'outputClass' => ConsoleOutput::class,
];
