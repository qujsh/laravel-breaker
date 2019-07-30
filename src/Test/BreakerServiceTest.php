<?php

namespace Qujsh\Breaker\Test;

use Qujsh\Breaker\IService\BreakerServiceInterface;

class BreakerServiceTest implements BreakerServiceInterface
{

    public function handle()
    {
        // TODO: Implement handle() method.

        return 'handle';
    }

    public function fall()
    {
        // TODO: Implement fall() method.

        return 'fallback';
    }
}

