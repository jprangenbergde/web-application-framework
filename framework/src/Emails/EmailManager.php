<?php

namespace Framework\Emails;

final readonly class EmailManager
{
    public function send(string $email, string $subject, string $content): void
    {
        var_dump("Sending email to $email with subject $subject and content: $content");
    }
}
