<?php
namespace app\common\exception;
use think\exception\Handle;
use think\Log;
use think\Request;
use Exception;
/*
 * 重写Handle的render方法，实现自定义异常消息
 */
class ExceptionHandler extends Handle
{
    public $code;             //http状态码
    public $msg;      //错误具体信息
    public $errorCode;      //自定义状态码
    public $url;      //跳转目录
    //还需要返回客户端请求的url路径
    //重写全局异常处理
    public function render(Exception $e)
     {
        //处理两种不同异常     1.不需要记录日志    ，2.需要记录日志
        if ($e instanceof BaseException)
        {
            //如果是自定义异常，则控制http状态码，不需要记录日志
            //因为这些通常是因为客户端传递参数错误或者是用户请求造成的异常
            //不应当记录日志
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
            $this->url = $e->url;
        }
        else{
            // 如果是服务器未处理的异常，将http状态码设置为500，并记录日志
            if(config('app_debug')){
                // 调试状态下需要显示TP默认的异常页面，因为TP的默认页面
                // 很容易看出问题
                return parent::render($e);
            }

            $this->code = 500;
            $this->msg = '服务器内部错误！';
            $this->errorCode = 9999;
            $this->recordErrorLog($e);   //服务器内部错误时，记录日志
        }

        $request = Request::instance();
        $result = [
            'msg'  => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result, $this->code);
    }

    /*
     * 将异常写入日志
     */
    private function recordErrorLog(Exception $e)
    {
        Log::init([
            'type'  =>  'File',
            'path'  =>  LOG_PATH_ADMIN,
            'level' => ['error']
        ]);
//        Log::record($e->getTraceAsString());
        Log::record($e->getMessage(),'error');
    }
}