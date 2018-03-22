<?php
/**
 * 加载类
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 19:22
 */
namespace core;
class Loader
{
    /**
     * 类名映射信息
     * @var array
     */
    protected static $map = [];

    /**
     * 类库别名
     * @var array
     */
    protected static $classAlias = [];

    /**
     * PSR-4
     * @var array
     */
    private static $prefixLengthsPsr4 = [];
    private static $prefixDirsPsr4    = [];
    private static $fallbackDirsPsr4  = [];

    /**
     * PSR-0
     * @var array
     */
    private static $prefixesPsr0     = [];
    private static $fallbackDirsPsr0 = [];

    /**
     * 自动加载的文件列表
     * @var array
     */
    private static $autoloadFiles = [];

    /**
     * Composer安装路径
     * @var string
     */
    private static $composerPath;

    /**
     * 注册自动加载机制
     * @param $autoload
     * @return mixed
     */
    public static function register($autoload = '')
    {
        spl_autoload_register($autoload ?: 'core\\Loader::autoload', true, true);
    }

    /**
     * 自动加载
     * @param $class
     * @return bool
     */
    public static function autoload($class)
    {
        if (isset(self::$classAlias[$class])) {
            return class_alias(self::$classAlias[$class], $class);
        }
    }

    /**
     * 注册命名空间
     * @param string $namespace
     * @param string $path
     */
    public function addNamespace($namespace, $path = '')
    {
        if (is_array($namespace)) {
            foreach ($namespace as $prefix => $paths) {
                self::addPsr4($prefix . '\\', rtrim($paths, DIRECTORY_SEPARATOR), true);
            }
        } else {
            self::addPsr4($namespace . '\\', rtrim($path, DIRECTORY_SEPARATOR), true);
        }
    }

    /**
     * 注册类库别名
     * @param array|string $alias
     * @param null $class
     * @return void
     */
    public static function addClassAlias($alias, $class = null)
    {
        if (is_array($alias)) {
            self::$classAlias = array_merge(self::$classAlias, $alias);
        } else {
            self::$classAlias[$alias] = $class;
        }
    }

    /**
     * 添加psr-4格式命名空间
     * @param string $prefix
     * @param string $paths
     * @param bool $prepend
     */
    public static function addPsr4($prefix, $paths, $prepend = false)
    {

    }

    /**
     * 加载composer包文件
     */
    public static function loadVendor()
    {

    }
}