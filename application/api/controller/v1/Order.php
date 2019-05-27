<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/25
 * Time: 21:37 下午
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\api\service\Token;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\lib\exception\OrderException;
use app\lib\exception\SuccessMessage;

class Order extends BaseController
{
    //用户在选择商量后,向API提交包含所选择商品的相关信息
    //API在接受到信息后,需要检查订单相关商品库存量(检测库存量)
    //有库存,把订单数据存入数据库中 => 下单成功，返回客户端消息，可以支付 => 支付接口，进行支付(检测库存量)
    //服务器调用微信支付接口进行支付
    //微信返回支付结果(检测库存量),根据返回值判断支付成功(异步调用)=>减去库存量
    //支付成功/失败 => 微信返回支付结果 =>成功(检测库存量) =>
    //成功：库存量扣除， 失败： 返回支付失败结果
    protected $beforeActionList = [
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'],
        'checkSuperScope' => ['only' => 'delivery,getSummary']
    ];
    /**
     * 下单
     * @http post
     */
    public function placeOrder()
    {
        // 通过token查询uid
        (new OrderPlace())->goCheck();
        $product = input('post.product/a');
//        return $product;
        $uid = Token::getCurrentUid();
        $order = new OrderService();
        $status = $order->place($uid, $product);
        return $status;
    }

    /**
     * 获取订单详情
     * @param $id
     * @return static
     * @throws OrderException
     * @throws \app\lib\exception\ParameterException
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
//        return $id;
        $orderDetail = OrderModel::get($id);
        if (!$orderDetail)
        {
            throw new OrderException();
        }
        return $orderDetail
            ->hidden(['prepay_id']);
    }

    /**
     * 根据用户id分页获取订单列表（简要信息）
     * @param int $page
     * @param int $size
     * @return array
     * @throws \app\lib\exception\ParameterException
     */
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uid = Token::getCurrentUid();
        $pagingOrders = OrderModel::getSummaryByUser($uid, $page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
//        $collection = collection($pagingOrders->items());
//        $data = $collection->hidden(['snap_items', 'snap_address'])
//            ->toArray();
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];

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
        $pagingOrders = OrderModel::getSummaryByPage($page, $size);
        if ($pagingOrders->isEmpty())
        {
            return [
                'current_page' => $pagingOrders->currentPage(),
                'data' => []
            ];
        }
        $data = $pagingOrders->hidden(['snap_items', 'snap_address'])
            ->toArray();
        return [
            'current_page' => $pagingOrders->currentPage(),
            'data' => $data
        ];
    }

    public function delivery($id){
        (new IDMustBePositiveInt())->goCheck();
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }
    // 取消订单
    public function deleteOrder($id)
    {
        (new IDMustBePositiveInt()) -> goCheck();
        $result = OrderModel::destroy($id);
        if($result) return true;
        return false;
    }
}