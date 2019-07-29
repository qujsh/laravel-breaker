<?php

namespace Qujsh\Breaker;

use Illuminate\Console\Scheduling\Schedule;
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



        //�� ����ע�ᵽ ��������ȥ��
        $this->commands([
            Command\SetBreakerHalfopen::class
        ]);

    }

}

