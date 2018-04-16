<?php

namespace core\packet;

use core\Container;
use core\Loader;
use core\facade\Request;
use core\facade\Response;

/**
 * 应用实现类
 * User: 郭冠常
 * @package core\packet
 */
class App implements \ArrayAccess
{

    const VERSION = '1.0';

    /**
     * 是否开启调试模式
     * @var bool
     */
    protected $isDebug = true;

    /**
     * 应用开始时间
     * @var float
     */
    protected $beginTime;

    /**
     * 应用内存初始占用(PHP进程占用)
     * @var int
     */
    protected $beginMem;

    /**
     * 应用根目录
     * @var string
     */
    protected $rootPath;

    /**
     * 应用命名空间
     * @var string
     */
    protected $namespace = 'app';

    /**
     * 应用类库目录
     * @var string
     */
    protected $appPath;

    /**
     * 框架目录
     * @var string
     */
    protected $corePath;

    /**
     * 运行时目录
     * @var string
     */
    protected $runtimePath;

    /**
     * 配置目录
     * @var string
     */
    protected $configPath;

    /**
     * 应用调度实例
     * @var Dispatch
     */
    protected $dispatch;

    /**
     * 容器实例
     * @var Container
     */
    protected $container;

    /**
     * App构架方法
     * @param string $appPath
     */
    public function __construct($appPath = '')
    {
        $this->appPath = $appPath ?: __DIR__ . '/../../../apps/';
        $this->corePath = $this->appPath . '../core/library/';
        $this->container = Container::getInstance();
        $this->beginTime = microtime(true);
        $this->beginMem = memory_get_usage();
        $this->rootPath = dirname(realpath($this->appPath)) . '/';
        $this->runtimePath = $this->rootPath . 'runtime/';
        $this->configPath = $this->rootPath . 'config/';
    }

    /**
     * 是否开启调试
     * @return bool
     */
    public function isDebug()
    {
        return $this->isDebug;
    }

    /**
     * 运行App
     * @return void
     */
    public function run()
    {
        $this->initialize();



        //使用调度执行应用


        //返回数据
        Response::send();
    }

    /**
     * 初始化应用
     * @return void
     */
    public function initialize()
    {
        //应用命名空间
        Loader::addNamespace($this->namespace, $this->appPath);
    }

    /**
     * 判断标识或类是否在容器
     * @param mixed $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->container->bound($key);
    }

    /**
     * 设置一个类或者标识到容器
     * @param mixed $key
     * @return object
     */
    public function offsetGet($key)
    {
        return $this->container->make($key);
    }

    /**
     * 绑定标识或者类到容器
     * @param mixed $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->container->bind($key, $value);
    }

    /**
     * 删除容器绑定的标识或者类
     * @param mixed $key
     */
    public function offsetUnset($key)
    {
        $this->container->unset($key);
    }
}