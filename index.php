<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// [ 应用入口文件 ]
if (!defined('__PUBLIC__')) {
    $_public = rtrim(dirname(rtrim($_SERVER['SCRIPT_NAME'], '/')), '/');
    define('__PUBLIC__', (('/' == $_public || '\\' == $_public) ? '' : $_public).'/public');
}
// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
//定义后台日志目录
define('LOG_PATH_ADMIN',  __DIR__.'/runtime/log/admin/');
//定义前台日志目录
define('LOG_PATH_HOME',  __DIR__.'/runtime/log/admin/');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';