<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/25
 * Time: 21:26 下午
 */


namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权限不够';
    public $errorCode = 10001;
}