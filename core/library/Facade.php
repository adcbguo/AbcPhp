<?php
/**
 * 工厂类
 * 调用服务容器创建实例
 * 绑定类代理
 * 静态调用
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 18:07
 */

namespace core;
class Facade
{
    /**
     * 绑定对象
     * @var array
     */
    protected static $bind = [];

    /**
     * 是否单例
     * @var bool
     */
    protected static $alwaysNewInstance = true;

    /**
     * 创建工厂实例
     * @param string $class
     * @param array $args
     * @param bool $newInstance
     * @return object
     */
    public static function createFacade($class = '', $args = [], $newInstance = false)
    {
        $class = $class ?: static::class;
        $facadeClass = static::getFacadeClass();
        if ($facadeClass) {
            $class = $facadeClass;
        } else if (isset(self::$bind[$class])) {
            $class = self::$bind[$class];
        }
        if (static::$alwaysNewInstance) {
            $newInstance = true;
        }
        return Container::getInstance()->make($class, $args, $newInstance);
    }

    /**
     * 获取类名
     * @return string
     */
    protected static function getFacadeClass()
    {
        return '';
    }

    /**
     * 带参数实例化当前Facade类
     * @param array ...$args
     * @return object
     */
    public static function instance(...$args)
    {
        return self::createFacade('', $args);
    }

    /**
     * 创建类的实例
     * @param string $class
     * @param array $args
     * @param bool $newInstance
     * @return object
     */
    public static function make($class = '', $args = [], $newInstance = false)
    {
        if (__CLASS__ != static::class) {
            return self::__callStatic('make', func_get_args());
        }
        if (true === $args) {
            $newInstance = true;
            $args = [];
        }
        return self::createFacade($class, $args, $newInstance);
    }

    /**
     * 绑定类的静态代理
     * @param array|string $name
     * @param null|string $class 类
     * @return void
     */
    public static function bind($name, $class = null)
    {
        if (__CLASS__ != static::class) {
            self::__callStatic('bind', func_get_args());
        }

        if (is_array($name)) {
            self::$bind = array_merge(self::$bind, $name);
        } else {
            self::$bind[$name] = $class;
        }
    }

    /**
     * 静态魔术方法
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        return call_user_func_array([static::createFacade(), $name], $args);
    }
}