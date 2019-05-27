<?php

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\lib\exception\FailedMessage;
use app\lib\exception\ProductException;
use app\lib\exception\SuccessMessage;

class Product
{
    protected $beforeActionList = [

        'checkSuperScope' => ['only' => 'delivery,getSummary,createOne,deleteOne']
    ];
    /**
     * 获取全部商品信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParameter())->goCheck();
        $pagingProducts = ProductModel::getSummaryByPage($page, $size);
        if ($pagingProducts->isEmpty())
        {
            return [
                'current_page' => $pagingProducts->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingProducts->hidden(['from','summary','img_id'])
            ->toArray();
        return [
            'current_page' => $pagingProducts->currentPage(),
            'data' => $data
        ];
    }
    /**
     * 根据类目ID获取该类目下所有商品(分页）
     * @url /product?id=:category_id&page=:page&size=:page_size
     * @param int $id 商品id
     * @param int $page 分页页数（可选)
     * @param int $size 每页数目(可选)
     * @return array of Product
     * @throws ParameterException
     */
    public function getByCategory($id = -1, $page = 1, $size = 30)
    {
        (new IDMustBePositiveInt())->goCheck();
        (new PagingParameter())->goCheck();
        $pagingProducts = ProductModel::getProductsByCategoryID($id, $page, $size);
        if ($pagingProducts->isEmpty()) {
            // 对于分页最好不要抛出MissException，客户端并不好处理
            return [
                'current' => $pagingProducts->currentPage(),
                'data' => []
            ];
        }
        //数据集对象和普通的二维数组在使用上的一个最大的区别就是数据是否为空的判断，
        //二维数组的数据集判断数据为空直接使用empty
        //collection的判空使用 $collection->isEmpty()
        // 控制器很重的一个作用是修剪返回到客户端的结果
        $data = $pagingProducts->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingProducts->currentPage(),
            'data' => $data
        ];
    }

    /**
     * 获取某分类下全部商品(不分页）
     * @url /product/all?id=:category_id
     * @param int $id 分类id号
     * @return \think\Paginator
     * @throws ProductException
     */
    public function getAllInCategory($id = -1)
    {
        (new IDMustBePositiveInt())->goCheck();
        $products = ProductModel::getProductsByCategoryID($id, false);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
        $data = $products->hidden(['summary'])->toArray();
        return $data;
    }

    /**
     * 获取指定数量的最近商品
     * @url /product/recent?count=:count
     * @param int $count
     * @return mixed
     * @throws ParameterException
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = ProductModel::getMostRecent($count);
        if ($products->isEmpty()) {
            throw new ProductException();
        }
//        $products = $products->hidden(['summary'])->toArry();
        $products = $products->hidden(['summary']);
        return $products;
    }

    /**
     * 获取商品详情
     * 如果商品详情信息很多，需要考虑分多个接口分布加载
     * @url /product/:id
     * @param int $id 商品id号
     * @return Product
     * @throws ProductException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        if (!$product) {
            throw new ProductException();
        }
        return $product;
    }

    public function createOne()
    {
        $product = new ProductModel();
        $product->save(['id'=>1]);
    }
    public function deleteOne($id)
    {
        ProductModel::destroy($id);
    }
    //更新Product状态
    public function delivery($id,$status){
        (new IDMustBePositiveInt())->goCheck();
        $success = ProductModel::delivery($id,$status);
        if($success){
            return new SuccessMessage();
        }
    }
    //添加或修改category
    public function addProduct($id = '',$name,$price,$stock,$category_id,$main_img_url = '')
    {
        $product = new ProductModel();
        $list['name'] = $name;
        $list['price'] = explode('￥',$price)[1];
        $list['stock'] = $stock;
        $list['category_id'] = $category_id;
        $list['main_img_url'] = '/'.$main_img_url;
        $__TEMP__ = ROOT_PATH . 'runtime/temp/uploads/';
        $__LOGO__ = ROOT_PATH . 'public/images/';
        if (empty($id)) {
            // 新增
            $result = $product->allowField(true)->save($list);
            if(!$result)  return new FailedMessage();
            rename($__TEMP__ . $main_img_url, $__LOGO__ . '/' . $main_img_url);
            return new SuccessMessage();
        } else {
            if(empty($main_img_url)){
                unset($list['main_img_url']);
            }else{
                rename($__TEMP__ . $main_img_url, $__LOGO__ . '/' . $main_img_url);
            }
            $result = $product->allowField(true)->save($list, ['id' => $id]);
            if(!$result)  return new FailedMessage();
            return new SuccessMessage();
        }

    }
}