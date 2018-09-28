<?php
return [
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    'pathinfo_depr'          => '/',
    'exception_handle'       => 'app\common\exception\ExceptionHandler',//重写异常目录
    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',     //File可以记录日志    test关闭记录日志
        // 日志保存目录
        'path'  => LOG_PATH_ADMIN,
        // 日志记录级别
        'level' => [],
    ],
];