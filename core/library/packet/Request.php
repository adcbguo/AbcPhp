<?php

namespace core\packet;
use core\facade\Config;
/**
 * 请求资源实现类
 * User: 郭冠常
 * @package core\packet
 */
class Request
{
    /**
     * 请求类型
     * @var string
     */
    protected $method;

    /**
     * 域名
     * @var string
     */
    protected $domain;

    /**
     * 当前模块名
     * @var string
     */
    protected $module;

    /**
     * 当前控制器名
     * @var string
     */
    protected $controller;

    /**
     * 当前操作名
     * @var string
     */
    protected $action;

    /**
     * 当前GET参数
     * @var array
     */
    protected $get = [];

    /**
     * 当前POST参数
     * @var array
     */
    protected $post = [];

    /**
     * 当前REQUEST参数
     * @var array
     */
    protected $request = [];

    /**
     * 当前PUT参数
     * @var array
     */
    protected $put;

    /**
     * 当前SERVER参数
     * @var array
     */
    protected $server = [];

    /**
     * 当前HEADER参数
     * @var array
     */
    protected $header = [];

    /**
     * php://input内容
     * @var string
     */
    protected $input;

    /**
     * 默认验证类
     * @var string
     */
    protected $filter;

    /**
     * 请求架构函数
     */
    public function __construct()
    {
        if (is_null($this->filter)) {
            $this->filter = Config::get('default_filter');
        }

        // 保存 php://input
        $this->input = file_get_contents('php://input');
    }
}