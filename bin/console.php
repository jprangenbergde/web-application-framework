<?php

declare(strict_types=1);

use App\Bootstrappers\AppBootstrapper;
use Framework\Application\Application;
use Framework\Consoles\CommandRunnerInterface;
use Framework\FactoryManagers\FactoryManager;

require_once __DIR__ . '/../bootstrap.php';

$application = new Application(
    new FactoryManager(),
    [
        AppBootstrapper::class,
    ]
);

$application->boot();

$commandRunner = $application->producer()->get(CommandRunnerInterface::class);
$commandRunner->run($argv);
