<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/24
 * Time: 10:18 上午
 */
namespace app\api\model;

class Category extends BaseModel
{
    protected $hidden = ['delete_time','update_time','description'];

    public function products()
    {
        return $this->hasMany('Product', 'category_id', 'id');
    }

    public function img()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    public static function getCategories($ids)
    {
        $categories = self::with('products')
            ->with('products.img')
            ->select($ids);
        return $categories;
    }

    public static function getCategory($id)
    {
        $category = self::with('products')
            ->with('products.img')
            ->find($id);
        return $category;
    }

    public static function getSummaryByPage($page=1, $size=20){
        $pagingData = self::where('status!=-1')->order('id asc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }

    public static function delivery($id,$status)
    {
        $result = self::where('id', '=',$id)
        ->update(['status' => $status]);
        return $result;
    }

}