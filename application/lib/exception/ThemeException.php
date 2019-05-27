<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/21
 * Time: 14:24 下午
 */


namespace app\lib\exception;


class ThemeException extends BaseException
{
    public $code = 404;
    public $msg = '指定主题不存在请检查主题ID';
    public $errorCode = 30000;
}