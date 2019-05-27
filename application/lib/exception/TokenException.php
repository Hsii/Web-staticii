<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 18:04 下午
 */


namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg = 'Token已过期或无效';
    public $errorCode = 10001;
}