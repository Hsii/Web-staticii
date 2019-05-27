<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 14:42 下午
 */
namespace app\api\service;
use app\lib\enum\ScopeEnum;
use app\lib\exception\WxChatException;
use app\lib\exception\TokenException;
use think\Exception;
use app\api\model\User;
class UserToken extends Token
{
    protected $code;
    protected $appID;
    protected $appSecret;
    protected $wxLoginUrl;

    function __construct($code)
    {
        $this->code = $code;
        $this->appID = config('wx.app_id');
        $this->appSecret = config('wx.app_secret');
        // 格式化字符串写入变量LoginUrl
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->appID, $this->appSecret, $this->code);

    }

    /**
     * 登陆
     * 思路1：每次调用登录接口都去微信刷新一次session_key，生成新的Token，不删除久的Token
     * 思路2：检查Token有没有过期，没有过期则直接返回当前Token
     * 思路3：重新去微信刷新session_key并删除当前Token，返回新的Token
     */
    public function get()
    {
        $result = curl_get($this->wxLoginUrl);
        // 注意json_decode的第一个参数true
        // 这将使字符串被转化为数组而非对象
        $wxResult = json_decode($result, true);
        if (empty($wxResult)) {
            // 为什么以empty判断是否错误，这是根据微信返回规则摸索出来的
            // 这种情况通常是由于传入不合法的code
            throw new Exception('获取session_key及openID时异常，微信内部错误');
        } else {
            // 建议用明确的变量来表示是否成功
            // 微信服务器并不会将错误标记为400，无论成功还是失败都标记成200
            // 这样非常不好判断，只能使用errcode是否存在来判断
            // 检查键名 "errorde" 是否存在于数组中$wxResult：(即调用失败)
            $loginFail = array_key_exists('errcode', $wxResult);
            if ($loginFail) {
                $this->processLoginError($wxResult);
            } else {
                return $this->grantToken($wxResult);
            }
        }
    }
    // 处理微信登陆异常
    // 那些异常应该返回客户端，那些异常不应该返回客户端
    // 需要认真思考
    //将微信错误信息返回JSON数据包替换WxChatException(示例为Code无效)
    private function processLoginError($WxResult)
    {
        throw new WxChatException([
            'msg' => $WxResult['errmsg'],
            'errorCode' => $WxResult['errcode']
        ]);
    }

    //写入缓存
    private function saveToCache($wxResult)
    {
        $key = self::generateToken();
        $value = json_encode($wxResult);
        $expire_in = config('setting.token_expire_in');

        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005,
            ]);
        }
        return $key;
    }
    // 颁发令牌
    // 只要调用登陆就颁发新令牌
    // 但旧的令牌依然可以使用
    // 所以通常令牌的有效时间比较短
    // 目前微信的express_in时间是7200秒
    // 在不设置刷新令牌（refresh_token）的情况下
    // 只能延迟自有token的过期时间超过7200秒（目前还无法确定，在express_in时间到期后
    // 还能否进行微信支付
    // 没有刷新令牌会有一个问题，就是用户的操作有可能会被突然中断
    private function grantToken($WxResult)
    {
        /*
         * 获取openid->数据库验证是否重复->0,不出来.1,新增user.->生成令牌，写入缓存->令牌返回客户端
         * key->令牌
         * value->WxResult,uid,scope
         **/
        $openid = $WxResult['openid'];
        //User模型查询数据库是否存在user
        $user = User::getByOpenID($openid);
        if (!$user)
            // 借助微信的openid作为用户标识
            // 但在系统中的相关查询还是使用自己的uid
        {
            $uid = $this->newUser($openid);
        } else {
            $uid = $user->id;
        }
        //生成缓存
        $cachedValue = $this->prepareCachedValue($WxResult, $uid);
        //写入缓存
        $token = $this->saveToCache($cachedValue);
        //返回令牌$token
        return $token;
    }
    //生成缓存
    private function prepareCachedValue($WxResult, $uid)
    {
        $cachedValue = $WxResult;
        $cachedValue['uid'] = $uid;
        //scope=16 APP用户权限数值
        $cachedValue['scope'] = ScopeEnum::User;
        //scope=32 CMS用户权限数值
//        $cachedValue['scope'] = 233;
        return $cachedValue;
    }
    //将新的openid写入数据库生成新的user记录
    private function newUser($openid)
    {
        // 有可能会有异常，如果没有特别处理
        // 这里不需要try——catch
        // 全局异常处理会记录日志
        // 并且这样的异常属于服务器异常
        // 也不应该定义BaseException返回到客户端
        $user = User::create([
            'openid' => $openid
        ]);
        return $user->id;
    }
}