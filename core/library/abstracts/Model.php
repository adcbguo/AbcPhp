<?php

namespace core\abstracts;
use core\packet\exception\HttpException;

/**
 * 框架基础模型文件
 * User: 郭冠常
 * @package core
 */
abstract class Model
{
    /**
     * 表名
     * @var string
     */
    protected static $name;

    /**
     * 主键
     * @var string
     */
    protected static $pk;

    /**
     * 所有的模型对象
     * @var array
     */
    protected static $instance;

    /**
     * 架构函数
     * @param string $name
     * @param string $pk
     */
    public function __construct($name = null, $pk = null)
    {
        empty($name) ?: self::$name = $name;
        empty($pk) ?: self::$pk = $pk;
    }

    /**
     * 实例化当前模型
     * @param string $name
     * @param string $pk
     * @return mixed
     */
    public static function instance($name = null, $pk = null)
    {
        if (!isset(self::$instance[static::class])) {
            self::$instance[static::class] = new static($name, $pk);
        }
        return self::$instance[static::class];
    }

    /**
     * 快捷静态调用方法
     * @param $name
     * @param $arguments
     * @return array|int|string
     * @throws HttpException
     */
    public static function __callStatic($name, $arguments)
    {
        if (false !== strpos($name, 'C')) {
            $method = substr($name, 1);
            return call_user_func_array([self::instance(), $method], $arguments);
        } else {
            throw new HttpException('did not find this method');
        }
    }
}