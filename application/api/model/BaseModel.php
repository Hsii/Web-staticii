<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //数据库图片地址拼接
    protected function prefixImgUrl($value,$data){
        $finaUrl = $value;
        //判断图片来源
        if($data['from'] ==1 ){
            $finaUrl = config('setting.img_prefix').$value;
        }
        return $finaUrl;
    }
}
