<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Registered;
use Framework\Emails\EmailJob;
use Framework\Events\EventManagers\EventAttacherInterface;
use Framework\Listeners\ObserverInterface;
use Override;

final class RegisteredEmailListener implements ObserverInterface
{
    #[Override]
    public function observe(EventAttacherInterface $attacher): void
    {
        $attacher->attach(
            Registered::class,
            fn(Registered $event) => $this->sendWelcomeEmailToUser($event)
        );

        $attacher->attach(
            Registered::class,
            fn(Registered $event) => $this->sendNewUserEmailToAdmin($event)
        );
    }

    private function sendWelcomeEmailToUser(Registered $event): void
    {
        EmailJob::queue(
            $event->email,
            "Welcome, $event->firstName $event->lastName",
            "Hello, $event->firstName $event->lastName!"
        );
    }

    private function sendNewUserEmailToAdmin(Registered $event): void
    {
        EmailJob::queue(
            'admin@prangenbergs.de',
            'New user',
            "New user with name `$event->firstName $event->lastName` registered!"
        );
    }
}
