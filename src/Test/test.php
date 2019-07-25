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

//失败的，需要引入
$breaker = new \Qujsh\Breaker\Service\Breaker(new \Qujsh\Breaker\Test\BreakerServiceTest());
var_dump($breaker);


