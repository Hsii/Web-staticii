<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2019/01/02
 * Time: 16:25 下午
 */

namespace app\lib\exception;

/**
 * 创建成功（如果不需要返回任何消息）
 * 201 创建成功，202需要一个异步的处理才能完成请求
 */
class SuccessMessage extends BaseException
{
    public $code = 201;
    public $msg = 'ok';
    public $errorCode = 0;
}