<?php

namespace core;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use InvalidArgumentException;

/**
 * 服务容器类
 * User: 郭冠常
 * @package core
 */
class Container
{
    /**
     * 容器对象实例
     * @var Container
     */
    protected static $instance;

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];

    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];

    /**
     * 实例化自身
     * @return Container
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * 获取容器中的对象实例
     * @param string $abstract
     * @param array $vars
     * @param bool $newInstance
     * @return object
     */
    public static function get($abstract, $vars = [], $newInstance = false)
    {
        return self::getInstance()->make($abstract, $vars, $newInstance);
    }

    /**
     * 去除容器内的绑定标识和类
     * @param $name
     */
    public function unset($name)
    {
        unset($this->bind[$name]);
        unset($this->instances[$name]);
    }

    /**
     * 判断容器中是否存在类及标识
     * @param $name
     * @return bool
     */
    public function bound($name)
    {
        return isset($this->bind[$name]) || isset($this->instances[$name]);
    }

    /**
     * 绑定类和闭包到容器
     * @param array|string $abstract 类标识,接口
     * @param null $concrete 类名称,闭包,对象
     */
    public function bind($abstract, $concrete = null)
    {
        if (is_array($abstract)) {
            $this->bind = array_merge($this->bind, $abstract);
        } elseif ($concrete instanceof Closure) {
            $this->bind[$abstract] = $concrete;
        } elseif (is_object($concrete)) {
            $this->instances[$abstract] = $concrete;
        } else {
            $this->bind[$abstract] = $concrete;
        }
    }

    /**
     * 创建类实例
     * @param $abstract
     * @param array $var
     * @param bool $newInstance 是否每次创建新的实例
     * @return object
     */
    public function make($abstract, $var = [], $newInstance = false)
    {
        if ($var === true) {
            $newInstance = true;
            $var = [];
        }
        if (isset($this->instances[$abstract]) && !$newInstance) {
            return $this->instances[$abstract];
        } else {
            if (isset($this->bind[$abstract])) {
                $concrete = $this->bind[$abstract];
                if ($concrete instanceof Closure) {
                    $object = $this->invokeFunction($abstract, $var);
                } else {
                    $object = $this->make($concrete, $var, $newInstance);
                }
            } else {
                $object = $this->invokeClass($abstract, $var);
            }
            if (!$newInstance) {
                $this->instances[$abstract] = $object;
            }
        }
        return $object;
    }

    /**
     * 调用反射执行函数或者闭包方法
     * @param Closure $function
     * @param array $vars
     * @return object
     */
    public function invokeFunction($function, $vars = [])
    {
        $reflect = new ReflectionFunction($function);
        $args = $this->bindParams($reflect, $vars);
        return $reflect->invokeArgs($args);
    }

    /**
     * 调用反射执行类的实例化
     * @param string $class
     * @param array $vars
     * @return object
     */
    public function invokeClass($class, $vars = [])
    {
        $reflect = new ReflectionClass($class);
        $constructor = $reflect->getConstructor();
        if ($constructor) {
            $args = $this->bindParams($constructor, $vars);
        } else {
            $args = [];
        }
        return $reflect->newInstanceArgs($args);
    }

    /**
     * 绑定参数到反射实例
     * @param ReflectionClass|ReflectionFunction $reflect
     * @param array $vars
     * @return array
     */
    protected function bindParams($reflect, $vars = [])
    {
        $args = [];
        if ($reflect->getNumberOfParameters() > 0) {
            reset($vars);
            $type = key($vars) === 0 ? 0 : 1;
            $params = $reflect->getParameters();
            foreach ($params as $index => $param) {
                $name = $param->getName();
                $class = $param->getClass();
                if ($class) {
                    $args[] = $this->make($class->getName());
                } elseif ($type == 0 && !empty($vars)) {
                    $args[] = array_shift($vars);
                } else if ($type == 1 && isset($vars[$name])) {
                    $args[] = $vars[$name];
                } else if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw new InvalidArgumentException('method param miss:' . $name);
                }
            }
        }
        return $args;
    }
}