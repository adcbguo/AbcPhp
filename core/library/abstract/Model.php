<?php

namespace core;
/**
 * 框架基础模型文件
 * User: 郭冠常
 * @package core
 */
abstract class Model
{
    /**
     * 表名(不带前缀)
     * @var string
     */
    protected $name;

    /**
     * 表名
     * @var string
     */
    protected $table;

    /**
     * 所有的模型对象
     * @var array
     */
    protected static $instance;

    /**
     * 实例化当前模型
     * @return Model
     */
    public static function instance()
    {
         if(!isset(self::$instance[static::class])){
             self::$instance[static::class] = new static();
         }
        return self::$instance[static::class];
    }

    /**
     * 快捷静态调用方法
     * @param $name
     * @param $arguments
     * @return array|int|string
     */
    public static function __callStatic($name, $arguments)
    {

        if(strpos($name,'C')){

        }
    }
}