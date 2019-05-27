<?php
/**
 * Created by PhpStorm.
 * User: Hsii
 * Date: 2018/09/25
 * Time: 22:35 下午
 */

namespace app\api\controller;

use app\api\service\Token;
use think\Controller;

class BaseController extends Controller
{
    //作用域判断
    protected function checkPrimaryScope(){
        Token::needPrimaryScope();
    }
    protected function checkExclusiveScope(){
        Token::needExclusiveScope();
    }
    protected function checkSuperScope()
    {
//        Token::needSuperScope();
        Token::needPrimaryScope();
    }
}