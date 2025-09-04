<?php

declare(strict_types=1);

namespace Framework\Application;

use Framework\FactoryManagers\FactoryProducerInterface;

interface ApplicationInterface extends
    ApplicationBooterInterface,
    ApplicationFlusherInterface,
    ApplicationHandlerInterface,
    ApplicationRunnerInterface
{
    public function producer(): FactoryProducerInterface;
}
