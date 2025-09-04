<?php

declare(strict_types=1);

use App\Http\Controllers\PageNotFoundController;

return [
    'controllers' => [
        'paths' => [
            __DIR__ . '/../src/Http/Controllers',
        ],
        'pageNotFoundControllerClass' => PageNotFoundController::class,
    ],
];
