<?php

namespace core\packet;
/**
 * 配置实现类
 * User: 郭冠常
 * @package core\packet
 */
class Config
{
    /**
     * 获取配置值
     * @param $name
     * @param string|array|int $default
     * @return array|string|int
     */
    public function get($name, $default = '')
    {
        return [];
    }

    /**
     * 设置配置
     * @param $name
     * @param $value
     * @return bool
     */
    public function set($name, $value)
    {
        if (is_array($name)) {

        } else {

        }
        return true;
    }
}