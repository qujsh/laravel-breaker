<?php

$circuit_key = 'circuit';
$circuit_halfopen = 'circuit_halfopen';

$circuit_status_open = 'circuit_open';
$circuit_status_halfopen = 'circuit_halfopen';
$circuit_status_close = 'circuit_close';

$attempts = config('breaker.default.attempts');
$interval = config('breaker.default.interval');

try{

    $attempts_fail = \Cache::get($circuit_key);
    if ($attempts_fail < 0){          //表示半开状态
        if (random_int(0, 99)%2){
            \Cache::increment($circuit_key);
            return $this->callback();
        }else{
            return $this->fallback();
        }

    }else{
        if ($attempts_fail < $attempts){
            return $this->callback();
        }else{
            return $this->fallback();
        }
    }

}catch (\Exception $e){

    if (\Cache::get($circuit_key)<0){
        \Cache::forever($circuit_key, $attempts);   //在半开状态下，如果执行失败，那么设置熔断器为开
        $score = $attempts;
    }else{
        $score = \Cache::increment($circuit_key);
    }

    if ($score >= $attempts){           //设置进入半开状态的时间
        \Cache::forever($circuit_halfopen, time()+$interval);
    }

    return $this->fallback();
}