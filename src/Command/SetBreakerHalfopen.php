<?php

namespace Qujsh\Breaker\Command;

use Illuminate\Console\Command;

class SetBreakerHalfopen extends Command
{
    // 供我们调用命令
    protected $signature = 'breaker:set-halfopen';

    // 命令的描述
    protected $description = 'set circuit breaker halfopen';

    // 最终执行的方法
    public function handle()
    {
        $cmd = system("ps -ef | grep 'artisan breaker:set-halfopen' | grep -v grep | wc -l");

        if ($cmd < 2 ){         //如果 执行这儿的脚本命令，那么默认 cmd == 1，
            // 在命令行打印一行信息
            $this->info("开始...");

            $config = config('breaker');
            while (true){

                //因为 可以根据配置文件做多service 不同情景使用，所以 做循环使用
                foreach ($config as $key => $value){
                    if (is_array($value)){

                        $circuit_key = 'circuit_'.$key;
                        $circuit_halfopen = 'circuit_halfopen_'.$key;
                        $attempts = $value['attempts'];

                        $time = \Cache::get($circuit_halfopen);
                        if ($time && $time<=time()){
                            \Cache::forever($circuit_key, 0-$attempts);          //设置 <0 为半开状态
                            \Cache::forget($circuit_halfopen);      //做删除，不然会一直执行下去
                        }
                    }else{
                        break;
                    }
                }

                usleep(500*1000);           //休眠500毫秒
            }

            $this->info("执行结束...");
        }else{
            $this->info("命令已开启");
        }
    }
}