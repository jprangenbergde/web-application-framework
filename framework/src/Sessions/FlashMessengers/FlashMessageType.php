<?php

declare(strict_types=1);

namespace Framework\Sessions\FlashMessengers;

enum FlashMessageType: string
{
    case Success = 'success';
    case Error = 'error';
    case Info = 'info';
    case Warning = 'warning';
}
