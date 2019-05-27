<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2019/01/03
 * Time: 14:13 下午
 */


namespace app\api\model;


class ThirdApp extends BaseModel
{
    public static function check($ac, $se)
    {
        $app = self::where('app_id', '=', $ac)
            ->where('app_secret', '=', $se)
            ->find();
        return $app;
    }
}