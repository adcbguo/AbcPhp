<?php
/**
 * 配置工厂类
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 9:24
 */
namespace core\packet;
class Config
{
    /**
     * 获取配置值
     * @param $name
     * @param string|array|int $default
     * @return array|string|int
     */
    public function get($name,$default)
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