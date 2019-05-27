<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/21
 * Time: 16:41 下午
 */


namespace app\api\validate;


class Count extends BaseValidate
{
    protected $rule =[
        'count' => 'isPositiveInteger|between:1,15'
    ];
}