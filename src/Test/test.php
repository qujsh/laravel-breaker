<?php


//功能失败，

require __DIR__ . '/../../vendor/autoload.php';

function config($key = null, $default = null){
    $config = require __DIR__ . '/../Config/breaker.php';

    $keys = explode('.', $key);

    foreach ($keys as $k=>$v){
        if ($k==0) continue;
        $config = $config[$v];
    }

    return $config;
}

echo config('breaker.default.attempts');

//失败的，需要引入
//$breaker = new \Qujsh\Breaker\Service\Breaker();
//$result = $breaker->handle(new \Qujsh\Breaker\Test\BreakerServiceTest());
//
//var_dump($result);


