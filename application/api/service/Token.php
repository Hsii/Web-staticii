<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 17:00 下午
 */
namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    // 生成令牌
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChars(32);
        //md5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }
    //
    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            //判断$vars数组中是否存在$key键
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }
    /**
     * 从缓存中获取当前用户指定身份标识
     * @param array $keys
     * @return array result
     * @throws \app\lib\exception\TokenException
     */
    public static function getCurrentIdentity($keys)
    {
        $token = Request::instance()
            ->header('token');
        $identities = Cache::get($token);
        //cache 助手函数有bug
//        $identities = cache($token);
        if (!$identities)
        {
            throw new TokenException();
        }
        else
        {
            $identities = json_decode($identities, true);
            $result = [];
            foreach ($keys as $key)
            {
                if (array_key_exists($key, $identities))
                {
                    $result[$key] = $identities[$key];
                }
            }
            return $result;
        }
    }
    /**
     * 当需要获取全局UID时，应当调用此方法
     *而不应当自己解析UID
     *
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        $scope = self::getCurrentTokenVar('scope');
        if ($scope == ScopeEnum::Super)
        {
            // 只有Super权限才可以自己传入uid
            // 且必须在get参数中，post不接受任何uid字段
            $userID = input('get.uid');
            if (!$userID)
            {
                throw new ParameterException(
                    [
                        'msg' => '没有指定需要操作的用户对象'
                    ]);
            }
            return $userID;
        }
        else
        {
            return $uid;
        }
    }
    /**
     * 检查操作UID是否合法
     * @param $checkedUID
     * @return bool
     * @throws Exception
     * @throws ParameterException
     */
    public static function isValidOperate($checkedUID)
    {
        if(!$checkedUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID == $checkedUID){
            return true;
        }
        return false;
    }
    //用户、管理员都可以访问的权限
    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope >= ScopeEnum::User){
            if($scope){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
    //只有用户才能访问的接口权限
    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if ($scope == ScopeEnum::User){
            if($scope){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }
    //只有管理员才能访问的接口权限
    public static function needSuperScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope){
            if ($scope == ScopeEnum::Super) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }

}