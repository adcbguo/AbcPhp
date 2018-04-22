<?php

namespace app\mobie\controller;

use core\abstracts\Controller;

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