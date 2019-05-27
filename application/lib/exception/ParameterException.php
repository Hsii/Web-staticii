<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/18
 * Time: 16:22 下午
 */

namespace app\lib\exception;

class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = 'ParameterException参数错误';
    public $errorCode = 10000;

}