<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
use think\Route;
//Route::get('getBanner/:id', 'api/v1.Banner/getBanner');
//路由动态传入版本号，调用不同版本的控制器
//Upload
Route::post('api/:version/Upload/uploadimg', 'api/:version.Upload/uploadimg');

//Banner
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//Theme
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');

//Product
Route::group('api/:version/product',function(){
    Route::get('api/:version/by_category','api/:version.Product/getAllInCategory');
    Route::get('api/:version/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
    Route::get('api/:version/recent','api/:version.Product/getRecent');
});
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
Route::post('api/:version/product/addProduct','api/:version.Product/addProduct');
Route::put('api/:version/product/editProduct','api/:version.Product/addProduct');
//CMS获取所有商品信息
Route::get('api/:version/product/paginate', 'api/:version.Product/getSummary');
Route::put('api/:version/product/delivery', 'api/:version.Product/delivery');

//Category
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');
Route::post('api/:version/category/addCategory','api/:version.Category/addCategory');
Route::put('api/:version/category/editCategory','api/:version.Category/addCategory');

//CMS获取所有分类信息
Route::get('api/:version/category/paginate', 'api/:version.Category/getSummary');
Route::put('api/:version/category/delivery', 'api/:version.Category/delivery');
//Token
Route::post('api/:version/token/user','api/:version.Token/getToken');
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::get('api/:version/token/app', 'api/:version.Token/getAppToken');
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken');

//Address
Route::post('api/:version/address','api/:version.Address/createrOrUpdateAddress');
Route::get('api/:version/address','api/:version.Address/getUserAddress');

//Order
Route::post('api/:version/order', 'api/:version.Order/placeOrder');
Route::get('api/:version/order/:id', 'api/:version.Order/getDetail',[], ['id'=>'\d+']);
Route::put('api/:version/order/delivery', 'api/:version.Order/delivery');
Route::post('api/:version/order/deleteOrder/:id', 'api/:version.Order/deleteOrder');

//不想把所有查询都写在一起，所以增加by_user，很好的REST与RESTFul的区别
Route::get('api/:version/order/by_user', 'api/:version.Order/getSummaryByUser');
//CMS获取所有订单信息
Route::get('api/:version/order/paginate', 'api/:version.Order/getSummary');

//Pay
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');
Route::post('api/:version/pay/notify', 'api/:version.Pay/receiveNotify');
Route::post('api/:version/pay/re_notify', 'api/:version.Pay/redirectNotify');
Route::post('api/:version/pay/concurrency', 'api/:version.Pay/notifyConcurrency');

//Message
Route::post('api/:version/message/delivery', 'api/:version.Message/sendDeliveryMsg');