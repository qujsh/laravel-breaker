<?php

namespace Qujsh\Breaker\Service;

use Qujsh\Breaker\IService\BreakerServiceInterface;

class Breaker {

    //最大失败尝试次数
    private $attempts;

    //时间轮询周期，即 熔断器开状态，多久时间转为半开状态
    private $interval;

    //缓存key值，用来做 attempts 对应计数
    private $circuit_key;

    //缓存key值，用来做 半开状态开启时间戳计数
    private $circuit_halfopen;

    public function __construct($mode = 'default')
    {
        $this->attempts = config("breaker.$mode.attempts");
        $this->interval = config("breaker.$mode.interval");
        $this->circuit_key = 'circuit_'.$mode;
        $this->circuit_halfopen = 'circuit_halfopen_'.$mode;
    }

    //执行熔断器
    public function handle(BreakerServiceInterface $breaker){

        $state = $this->getState();         //获得熔断器状态，做不同类型熔断器处理
        try{
            switch ($state){
                case config('breaker.state_close'):
                    return $breaker->handle();
                case config('breaker.state_open'):
                    return $breaker->fall();
                case config('breaker.state_halfopen'):
                    if ($this->getRandom()){
                        \Cache::increment($this->circuit_key);
                        return $breaker->handle();
                    }else{
                        return $breaker->fall();
                    }
            }

        }catch (\Exception $e){

            if ($state == config('breaker.state_halfopen')){
                \Cache::forever($this->circuit_key, $this->attempts);   //在半开状态下，如果执行失败，那么设置熔断器为开
                $score = $this->attempts;
            }else{
                $score = \Cache::increment($this->circuit_key);
            }

            if ($score >= $this->attempts){           //设置进入半开状态的时间
                \Cache::forever($this->circuit_halfopen, time()+$this->interval);
            }

            return $breaker->fall();
        }
    }

    //获得随机生成数
    public function getRandom(){
        return random_int(0, 99)%2;             //如果为1，执行正常函数；如果为0，执行失败函数
    }

    public function getAttempts(){
        return $this->attempts;
    }

    public function getInterval(){
        return $this->interval;
    }

    //获得当前熔断器状态
    public function getState(){
        $attempts_fail = \Cache::get($this->circuit_key);

        if ($attempts_fail < 0) return config('breaker.state_halfopen');
        if ($attempts_fail >= $this->attempts ) return config('breaker.state_open');
        return config('breaker.state_close');
    }
}

