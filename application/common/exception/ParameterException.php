<?php
namespace app\common\exception;
/**
 * Class ParameterException
 * 通用参数类异常错误
 */
class ParameterException extends BaseException
{
    public $code = 400;
    public $errorCode = 10000;
    public $msg = "参数错误！";
    public $url = '';
}