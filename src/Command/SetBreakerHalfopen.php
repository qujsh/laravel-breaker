<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetBreakerHalfopen extends Command
{
    // 供我们调用命令
    protected $signature = 'breaker:set-halfopen';

    // 命令的描述
    protected $description = 'set breaker halfopen';

    // 最终执行的方法
    public function handle()
    {
        // 在命令行打印一行信息
        $this->info("开始...");

        $circuit_key = 'circuit';
        $circuit_halfopen = 'circuit_halfopen';
        $attempts = config('breaker.default.attempts');

        while (true){

            $time = \Cache::get($circuit_halfopen);
            if ($time && $time<=time()){
                \Cache::forever($circuit_key, 0-$attempts);          //设置 <0 为半开状态
                \Cache::forget($circuit_halfopen);      //做删除，不然会一直执行下去
            }

            usleep(500*1000);           //休眠500毫秒
        }


        $this->info("执行结束...");
    }
}