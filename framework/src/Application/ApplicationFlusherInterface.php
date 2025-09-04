<?php

declare(strict_types=1);

namespace Framework\Application;

interface ApplicationFlusherInterface
{
    public function flush(): ApplicationRunnerInterface;
}
