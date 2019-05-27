<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/18
 * Time: 10:17 上午
 * 统一描述错误封装: 错误码、错误信息、当前URL
 */


namespace app\lib\exception;

use think\Exception;
use Throwable;

class BaseException extends Exception
{
    // HTTP 状态码 404,200
    public $code = 400;
    // 错误具体信息
    public $msg = '这是自定义异常报错！';
    // 自定义的错误码
    public $errorCode = 10000;
    public function __construct($params = [])
    {
        if(!is_array($params)){
            return;
            //throw new Exception('这是BaseException.参数必须是数组!');
        }
        if(array_key_exists('code',$params)){
            $this->code = $params['code'];
        }
        if(array_key_exists('msg',$params)){
            $this->msg = $params['msg'];
        }
        if(array_key_exists('errorCode',$params)){
            $this->errorCode = $params['errorCode'];
        }
    }
}