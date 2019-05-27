<?php
namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        // 获取http传入的参数
        // 对这些参数做检验
        $request = Request::instance();
        $params = $request->param();

        $result = $this->batch()->check($params);
        if(!$result){
            $e = new ParameterException([
                'msg' => $this->error,
            ]);
            throw $e;
        }
        else{
            return true;
        }
    }
    //$value一般为字符串，+0 强制转换为整型变量
    protected function isPositiveInteger($value,$rule='',$data='',$filed=''){
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }else{
            return false;
        }
    }
    protected function isNotEmpty($value,$rule='',$data='',$filed=''){
        if(!empty($value)){
            return true;
        }else{
            return false;
        }
    }
    protected function isMobile($value){
        $rule = '^1(3|4|5|6|7|8|9)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function getDataByRule($arrarys){
        if(array_key_exists('user_id',$arrarys) | array_key_exists('uid',$arrarys)){
            //不允许包含user_id或者uid,防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        //this->rule 指向protected $rule传入数组的值
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrarys[$key];
        }
        return $newArray;

    }
}