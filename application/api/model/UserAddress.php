<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/25
 * Time: 10:31 上午
 */


namespace app\api\model;


class UserAddress extends BaseModel
{
    protected $hidden = ['id','delete_time','user_id'];
}