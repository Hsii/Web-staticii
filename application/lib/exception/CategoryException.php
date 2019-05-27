<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/25
 * Time: 22:28 下午
 */


namespace app\lib\exception;


class CategoryException extends BaseException
{
    public $code = 404;
    public $msg = '指定类目不存在，请检查商品ID';
    public $errorCode = 20000;
}