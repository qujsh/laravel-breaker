<?php

namespace Qujsh\Breaker\IService;

interface BreakerServiceInterface{
    public function handle();           //正确执行
    public function fall();             //失败执行
}

