<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 15:44 下午
 */

namespace app\lib\exception;

class WxChatException extends BaseException
{
    public $code = 400;
    public $msg = '微信服务器接口调用失败';
    public $errorCode = 999;
}