<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/17
 * Time: 20:56 下午
 */

namespace app\api\controller\v1;

use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\BannerMissException;

class Banner
{

     /**
     * 获取指定id的banner信息
     * @url /banner/:id
     * @http GET
     * @id banner的id号
     *     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();

        $banner = BannerModel::getBannerByID($id);
        //数组化
//        $data = $banner->toArray();
        //对数组对象进行隐藏
//        unset($data['delete_time'],$data['update_time']);
//        $banner->hidden(['update_time','delete_time']);
//        $banner->visible(['id','name','description']);
        if (!$banner){
            //log('error');
            throw new BannerMissException();
        }
        return $banner;

    }

}