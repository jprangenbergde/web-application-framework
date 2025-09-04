<?php

namespace Framework\Emails;

use Framework\Jobs\Job;
use Framework\Queues\QueuePriority;
use Override;

final readonly class EmailJob extends Job
{
    public function __construct(
        public string $email,
        public string $subject,
        public string $content
    ) {}

    #[Override]
    public function getQueuePriority(): QueuePriority
    {
        return QueuePriority::Low;
    }

    public function execute(EmailManager $emailManager): void
    {
        $emailManager->send($this->email, $this->subject, $this->content);
    }
}
