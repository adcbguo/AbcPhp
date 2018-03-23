<?php

namespace core;
/**
 * 框架基础控制器
 * User: 郭冠常
 * @package core
 */
abstract class Controller
{
    /**
     * 返回网页数据
     * @param array $result
     * @param string $msg
     * @param int $code
     * @return string
     */
    public function send($result = [], $msg = '', $code = 200)
    {
        return json_encode([]);
    }
}