<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/24
 * Time: 17:04 下午
 */


namespace app\api\validate;


class AddressNew extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require|isMobile',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty'
    ];
}