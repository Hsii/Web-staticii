<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2019/01/03
 * Time: 18:28 下午
 */
namespace app\api\behavior;

class CORS
{
    public function appInit(&$params)
    {
         ('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: token,Origin,X-Requested-Width,');
        header('Access-Control-Allow-Methods: POST,GET');
        if(request()->isOptions())
        {
            exit();
        }
    }
}