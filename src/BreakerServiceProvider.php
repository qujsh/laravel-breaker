<?php

namespace Qujsh\Breaker;

use Illuminate\Support\ServiceProvider;

class BreakerServiceProvider extends ServiceProvider{

    /**
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/breaker.php' => config_path('breaker.php'),
        ], 'config');

        $this->commands([
            Qujsh\Breaker\Command\SetBreakerHalfopen::class
        ]);

    }

}

