<?php

namespace Qujsh\Breaker\Test;

use Qujsh\Breaker\IService\BreakerServiceInterface;

class BreakerServiceTest implements BreakerServiceInterface
{

    public function handle()
    {
        // TODO: Implement handle() method.

        echo 'handle';
    }

    public function fall()
    {
        // TODO: Implement fall() method.

        echo 'fallback';
    }
}

