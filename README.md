# laravel-breaker
基于laravel的简易熔断器实现，

## Install
laravel5.6 以上版本
``` bash
composer require qujsh/laravel-breaker
```

使用下面命令来生成配置文件，和注册命令行任务
```bash
php artisan vendor:publish --provider="Qujsh\Breaker\BreakerServiceProvider"
```

命令行定时任务监听，判断进程是否一直开启， App\Console\Kernel.php
```bash
protected function schedule(Schedule $schedule)
{
    //每分钟执行一次命令判断，使 监听程序一直开启
    $schedule->command('breaker:set-halfopen')->everyMinute();
}
```

## Usage
自定义一个类，定义正常 handle()函数，和降级 fall()函数使程序能运行。示例：
```bash
namespace App\Breaker;

use Qujsh\Breaker\IService\BreakerServiceInterface;

class Breaker implements BreakerServiceInterface{

    public function handle()
    {
        // TODO: Implement handle() method.

        // success function to do something
        
        //throw new \Exception('test throw');
        return 'handle';
    }

    public function fall()
    {
        // TODO: Implement fall() method.

        //fall function to do, when handle() is fail 

        return 'fall';
    }
}

//callback execute
$breaker = new Qujsh\Breaker\Service\Breaker();
$result = $breaker->handle(new \App\Breaker\Breaker());
```

## Config
### default
```bash
return [
    'default' => [
        'attempts' => 3,           //times, when reach this value, breaker is open
        'interval' => 180,         //interval, it's time to change state from open to halfopen 
    ],

    //multi array with diff key should be defined over there
    
    'state_open' => 1,              //breaker state open
    'state_halfopen' => 2,          //breaker state halfopen
    'state_close' => 3,             //breaker state close, default
];
```
- default：默认service，包含 attempts 和 interval，可定义多组，使用不同key值
- attempts：尝试次数，几次后开启熔断器
- interval：多久时间后转换状态为半开启， 时间单位 秒
