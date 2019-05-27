<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 14:41 下午
 */
namespace app\api\model;

use think\Model;

class User extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function orders()
    {
        return $this->hasMany('Order', 'user_id', 'id');
    }
    //一对一
    public function address(){
        return $this->hasOne('UserAddress','user_id','id');
    }
    /**
     * 用户是否存在
     * 存在返回uid，不存在返回0
     */
    public static function getByOpenID($openid)
    {
        $user = User::where('openid', '=', $openid)
            ->find();
        return $user;
    }

}