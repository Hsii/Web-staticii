<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/18
 * Time: 10:20 上午
 */


namespace app\lib\exception;


class BannerMissException extends BaseException
{
    public $code = 404;
    public $msg = 'BannerMissException,请求的Banner不存在';
    public $errorCode = 40000;
}