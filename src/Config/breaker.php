<?php

return [
    'default' => [
        'attempts' => 3,           //尝试次数，几次后开启熔断器
        'interval' => 180,         //多久时间后转换状态为半开启， 时间单位 秒
    ],

    'state_open' => 1,              //熔断器开启状态， 正常不用做这儿的设置，只是一份参考
    'state_halfopen' => 2,          //熔断器半开状态
    'state_close' => 3,             //熔断器关闭状态，默认

];

