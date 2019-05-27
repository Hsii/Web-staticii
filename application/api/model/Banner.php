<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/18
 * Time: 9:43 上午
 */

namespace app\api\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time','delete_time'];

    //模型关联,hasMany一对多关联
    public function items(){
        return $this->hasMany('BannerItem','banner_id','id'); //关联模型名字、关联模型外键、当前模型主键
    }
//    protected $table = 'category';
    /**
     * @param $id
     * @return mixed
     */
    public static function getBannerByID($id){
//        $result = Db::query('SELECT * FROM banner_item WHERE banner_id=?',[$id]);
//        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}