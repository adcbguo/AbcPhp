<?php
/**
 * 用户控制器
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 10:51
 */
namespace app\mobie\controller;
use core\Controller;
class User extends Controller
{
    /**
     * 用户登录
     * @return \core\packet\Response
     */
    public function login()
    {
        return $this->result()->send();
    }

    /**
     * 用户注册
     * @return \core\packet\Response
     */
    public function register()
    {
        return $this->result()->send();
    }
}