<?php

declare(strict_types=1);

use App\Bootstrappers\AppBootstrapper;
use Framework\Application\Application;
use Framework\FactoryManagers\FactoryManager;
use Framework\Http\Middlewares\Security\CsrfTokenMiddleware;

(static function (): void {
    require_once __DIR__ . '/../bootstrap.php';
})();

$application = new Application(
    new FactoryManager(),
    [
        AppBootstrapper::class,
    ],
    [
        CsrfTokenMiddleware::class,
    ],
);

$application->boot()->run();
