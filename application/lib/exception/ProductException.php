<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/21
 * Time: 17:12 下午
 */


namespace app\lib\exception;


class ProductException extends BaseException
{
    public $code = 404;
    public $msg = '请求商品不存在,请检查参数';
    public $errorCode = 20000;
}