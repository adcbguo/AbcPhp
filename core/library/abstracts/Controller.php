<?php

namespace core\abstracts;
use core\facade\Response;

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
     * @return \core\packet\Response
     */
    public function result($result = [], $msg = '', $code = 200)
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'data' => $result
        ];
        return Response::setDate($result);
    }
}