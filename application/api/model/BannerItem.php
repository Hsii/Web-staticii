<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden = ['id','from','update_time','delete_time'];

    //模型关联,belongsTo一对一关联
    public function img(){
        return $this->belongsTo('Image','img_id','id');
    }
}
