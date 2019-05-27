<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/22
 * Time: 10:04 上午
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Category as CategoryModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\PagingParameter;
use app\api\validate\PreOrder;
use app\lib\exception\FailedMessage;
use app\lib\exception\MissException;
use app\lib\exception\SuccessMessage;

class Category extends BaseController
{
    protected $beforeActionList = [
        'checkSuperScope' => ['only' => 'delivery,getSummary']
    ];

    /**
     * 获取全部类目列表，但不包含类目下的商品
     * Request 演示依赖注入Request对象
     * @url /category/all
     * @return array of Categories
     * @throws MissException
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all(['status'=>0], 'img');
        if (empty($categories)) {
            throw new MissException([
                'msg' => '还没有任何类目',
                'errorCode' => 50000
            ]);
        }
        return $categories;
    }

    /**
     * 这里没有返回类目的关联属性比如类目图片
     * 只返回了类目基本属性和类目下的所有商品
     * 返回什么，返回多少应该根据团队情况来考虑
     * 为了接口通用性可以返回大量的无用数据
     * 也可以只返回客户端需要的数据，但这会造成有大量重复接口
     * 接口应当和业务绑定还是和实体绑定需要团队自己抉择
     * 此接口主要是为了返回分类下面的products，请对比products中的
     * 接口，这是一种不好的接口设计
     * @url /category/:id/products
     * @return Category single
     * @throws MissException
     */
    public function getCategory($id)
    {
//        $validate = new IDMustBePositiveInt();
//        $validate->goCheck();
        $category = CategoryModel::getCategory($id);
        if (empty($category)) {
            throw new MissException([
                'msg' => 'category not found'
            ]);
        }
        return $category;
    }
    /**
     * 获取全部订单简要信息（分页）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummary($page=1, $size = 20){
        (new PagingParameter())->goCheck();
//        $uid = Token::getCurrentUid();
        $pagingCategories = CategoryModel::getSummaryByPage($page, $size);
        if ($pagingCategories->isEmpty())
        {
            return [
                'current_page' => $pagingCategories->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingCategories->hidden(['topic_img_id'])
            ->toArray();
        return [
            'current_page' => $pagingCategories->currentPage(),
            'data' => $data
        ];
    }
    //更新category状态
    public function delivery($id,$status){
        (new IDMustBePositiveInt())->goCheck();
        $success = CategoryModel::delivery($id,$status);
        if($success){
            return new SuccessMessage();
        }
    }
    //添加或修改category
    public function addCategory($id = '',$name)
    {
        $category = new CategoryModel();
        if (!empty($name)) {
            if(empty($id)){
                $category->allowField(true)->save(['name' => $name]);
            }else{
                $list = ['name' => $name ,
                        'update_time' => time()
                ];
                $category->allowField(true)->save($list,['id' => $id]);
            }
            return new SuccessMessage();
        } else {
            return new FailedMessage();
        }
    }
}