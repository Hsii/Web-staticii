<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/18
 * Time: 10:14 上午
 */

namespace app\lib\exception;

use Exception;
use think\Config;
use think\Exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    // 需要返回客户端当前请求的URL路径

    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            // 自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
//            Config::get('app_debug');
            if(config('app_debug')){
                // return default error page
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = '服务器内部错误！注意:这不是自定义异常!';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }
        }
        $result = Request::instance();

        $result = [
            'msg' => $this->msg,
            'error_code' => $this->errorCode,
            'request_url' => $result->url()
        ];
        return json($result,$this->code);
    }
    private function recordErrorLog(Exception $e){
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}