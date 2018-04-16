<?php

namespace core\packet;

use core\facade\App;

/**
 * 配置实现类
 * User: 郭冠常
 * @package core\packet
 */
class Config implements \ArrayAccess
{
    /**
     * 配置数据
     * @var array
     */
    private $config = [];

    /**
     * 获取配置值
     * @param $name
     * @param string|array|int $default
     * @return array|string|int
     */
    public function get($name, $default = '')
    {
        if (!isset($this->config[$name])) {
            $arr = $this->autoLoad($name);
            $this->config[$name] = empty($arr) ? $default : $arr;
        }
        return $this->config[$name];
    }

    /**
     * 设置配置
     * @param string|array $name
     * @param string|array $value
     * @return array
     */
    public function set($name, $value)
    {
        if (is_array($name)) {
            $this->config = array_merge($this->config, $name);
        } else {
            $this->config[$name] = $value;
        }
        return $this->config;
    }

    /**
     * 加载配置
     * @param string $name
     * @return bool|mixed
     */
    private function autoLoad($name)
    {
        $file = App::getConfigPath() . $name . '.php';
        if (is_file($file)) {
            return include_once $file;
        }
        return false;
    }

    /**
     * 是否存在配置参数
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * 移除配置项
     * @param string $name
     */
    public function remove($name)
    {
        unset($this->config[$name]);
    }

    /**
     * 获取配置参数
     * @access public
     * @param  string $name 参数名
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 检测是否存在参数
     * @access public
     * @param  string $name 参数名
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * 设置值
     * @param string|array $name
     * @param string|array $value
     */
    public function offsetSet($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * 检测是否存在参数
     * @param string $name
     * @return bool
     */
    public function offsetExists($name)
    {
        return $this->has($name);
    }

    /**
     * 移除配置项
     * @return void
     */
    public function offsetUnset($name)
    {
        $this->remove($name);
    }

    /**
     * 获取配置项
     * @param string $name
     * @return array|int|mixed|string
     */
    public function offsetGet($name)
    {
        return $this->get($name);
    }
}