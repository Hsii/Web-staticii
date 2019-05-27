<?php

namespace app\api\model;

class Product extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time', 'pivot', 'main_img_id', 'from'];
    protected $autoWriteTimestamp = true;
    //调用BaseModel进行图片url拼接
    public function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    //模型关联,一对多,商品图片
    public function imgs()
    {
        return $this->hasMany('ProductImage', 'product_id', 'id');
    }

    //模型关联,一对多,商品属性参数
    public function properties()
    {
        return $this->hasMany('ProductProperty', 'product_id', 'id');
    }

    //查询最新商品
    public static function getMostRecent($count)
    {
        $products = self::limit($count)->order('create_time desc')->select();
        return $products;
    }
    public static function getSummaryByPage($page=1, $size=20){
        $pagingData = self::where('status!=-1')->order('create_time asc')
            ->paginate($size, true, ['page' => $page]);
        return $pagingData;
    }
    /**
     * 获取某分类下商品
     * @param $categoryID
     * @param int $page
     * @param int $size
     * @param bool $paginate
     * @return \think\Paginator
     */
    public static function getProductsByCategoryID(
        $categoryID, $paginate = true, $page = 1, $size = 30)
    {
        $query = self::where('category_id =' . $categoryID . ' AND status = 0');
        if(!$paginate)
        {
            return $query->select();
        }else{
            // paginate 第二参数true表示采用简洁模式，简洁模式不需要查询记录总数
            return $query->paginate($size,true,[
                'page' => $page
            ]);
        }
    }
    /**
     * 获取商品详情
     * @param $id
     * @return null | Product
     */
    public static function getProductDetail($id)
    {
//        $products = self::with(['imgs.imgUrl','properties'])->find($id);
        $products = self::with([
            'imgs' => function ($query) {
                $query->with(['imgUrl'])
                    ->order('order', 'asc');
            }
        ])
            ->with(['properties'])
            ->find($id);
        return $products;
    }
    public static function delivery($id,$status)
    {
        $result = self::where('id', '=',$id)
            ->update(['status' => $status]);
        return $result;
    }
}