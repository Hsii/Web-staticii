<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/24
 * Time: 17:51 下午
 */


namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 404;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}