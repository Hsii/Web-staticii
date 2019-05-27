<?php

namespace app\api\model;

use think\Model;

class Theme extends BaseModel
{
    protected $hidden = ['update_time','delete_time','topic_img_id','head_img_id'];
    //关联关系
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }
    public function headImg(){
        return $this->belongsTo('Image','head_img_id','id');
    }
    //多对多关系
    public function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }
    public static function getThemeWithProducts($id){
        $theme = self::with('products,topicImg,headImg')->find($id);
        return $theme;
    }
}
