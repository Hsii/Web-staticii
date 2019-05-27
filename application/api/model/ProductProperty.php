<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/24
 * Time: 10:20 上午
 */


namespace app\api\model;


class ProductProperty extends BaseModel
{
    protected $hidden = ['id','delete_time','update_time',  'product_id'];
}