<?php

declare(strict_types=1);

use Framework\Queues\Database\DatabaseQueue;

return [
    'pdo' => [
        'dsn' => 'sqlite:' . __DIR__ . '/../data/database.db',
    ],

    // Transactional Outbox: Nachrichten werden transaktional in `outbox_messages` geschrieben
    // und per `outbox:relay` an den Transport weitergeleitet.
    'outbox' => [
        'enabled' => true,
        'transport' => DatabaseQueue::class,
        'batchSize' => 100,
        'maxAttempts' => 5,
    ],
];
