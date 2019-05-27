<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/24
 * Time: 10:18 上午
 */


namespace app\api\model;

use think\Model;

class ProductImage extends BaseModel
{
    protected $hidden = ['img_id','delete_time','product_id'];
    //模型关联，一对一关系
    public function imgUrl(){
        return $this->belongsTo('Image','img_id','id');
    }
}