<?php

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id','from','update_time','delete_time'];

    // url指代的image中的url地址字段
    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }

}
