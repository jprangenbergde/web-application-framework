<?php

declare(strict_types=1);

use Framework\Events\LifecycleEvents\ExceptionOccurred;

require_once __DIR__ . '/vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (class_exists(Whoops\Run::class) && env('APP_DEBUG') === 'true') {
    $whoops = new Whoops\Run();

    $handler = new Whoops\Handler\PrettyPageHandler();
    $handler->setEditor(env('WHOOPS_IDE'));

    if (Whoops\Util\Misc::isCommandLine()) {
        $handler = new Whoops\Handler\PlainTextHandler();
    } elseif (Whoops\Util\Misc::isAjaxRequest()) {
        $handler = new Whoops\Handler\JsonResponseHandler();
    }

    $whoops->pushHandler($handler);
    $whoops->pushHandler(
        new Whoops\Handler\CallbackHandler(
            static function (Throwable $throwable): void {
                ExceptionOccurred::occur($throwable);
            }
        )
    );
    $whoops->register();
} else {
    set_exception_handler(static function (Throwable $throwable): void {
        ExceptionOccurred::occur($throwable);
    });
}
