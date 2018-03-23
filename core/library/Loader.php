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
     * @return void
     */
    public static function register($autoload = '')
    {
        //注册系统自动加载
        spl_autoload_register($autoload ?: 'core\\Loader::autoload', true, true);

        //注册框架命名空间
        self::addNamespace(['core' => __DIR__ . '/']);

        //composer包目录
        self::$composerPath = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/../') . '/' . 'vendor/composer/';

        //注册自动加载composer包文件
        if (is_dir(self::$composerPath)) {
            self::registerVendor(self::$composerPath);
        }
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
        if ($file = self::findFile($class)) {
            if (strpos(PHP_OS, 'WIN') !== false && pathinfo($file, PATHINFO_FILENAME) != pathinfo(realpath($file), PATHINFO_FILENAME)) {
                return false;
            }
            include_file($file);
            return true;
        }
        return true;
    }

    /**
     * 查找文件(PSR-4,PSR-0,类库映射)
     * @param string $class
     * @return bool|mixed|string
     */
    public static function findFile($class)
    {
        //是否有类库映射
        if (!empty(self::$map[$class])) {
            return self::$map[$class];
        }
        //PSR-4格式文件
        $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . '.php';
        $first = $class[0];
        if (isset(self::$prefixLengthsPsr4[$first])) {
            foreach (self::$prefixLengthsPsr4[$first] as $prefix => $length) {
                if (0 === strpos($class, $prefix)) {
                    foreach (self::$prefixDirsPsr4[$prefix] as $dir) {
                        if (is_file($file = $dir . DIRECTORY_SEPARATOR . substr($logicalPathPsr4, $length))) {
                            return $file;
                        }
                    }
                }
            }
        }
        //PSR-4格式后备目录
        foreach (self::$fallbackDirsPsr4 as $dir) {
            if (is_file($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr4)) {
                return $file;
            }
        }
        //PSR-0格式文件
        if (false !== $pos = strrpos($class, '\\')) {
            $logicalPathPsr0 = substr($logicalPathPsr4, 0, $pos + 1) . strtr(substr($logicalPathPsr4, $pos + 1), '_', DIRECTORY_SEPARATOR);
        } else {
            $logicalPathPsr0 = strtr($class, '_', DIRECTORY_SEPARATOR) . '.php';
        }
        if (isset(self::$prefixesPsr0[$first])) {
            foreach (self::$prefixesPsr0[$first] as $prefix => $dirs) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($dirs as $dir) {
                        if (is_file($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr0)) {
                            return $file;
                        }
                    }
                }
            }
        }
        //PSR-0格式后备目录
        foreach (self::$fallbackDirsPsr0 as $dir) {
            if (is_file($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr0)) {
                return $file;
            }
        }
        return self::$map[$class] = false;
    }

    /**
     * 注册命名空间
     * @param string|array $namespace
     * @param string $path
     */
    public static function addNamespace($namespace, $path = '')
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
     * @param string|array $paths
     * @param bool $prepend
     */
    public static function addPsr4($prefix, $paths, $prepend = false)
    {
        if (!$prefix) {
            if ($prepend) {
                self::$fallbackDirsPsr4 = array_merge((array)$paths, self::$fallbackDirsPsr4);
            } else {
                self::$fallbackDirsPsr4 = array_merge(self::$fallbackDirsPsr4, (array)$paths);
            }
        } elseif (!isset(self::$prefixDirsPsr4[$prefix])) {
            $len = strlen($prefix);
            if ('\\' != $prefix[$len - 1]) {
                throw new \InvalidArgumentException('A non-empty PSR-4 prefix must end with a namespace separator.');
            }
            self::$prefixLengthsPsr4[$prefix[0]][$prefix] = $len;
            self::$prefixDirsPsr4[$prefix] = (array)$paths;
        } elseif ($prepend) {
            self::$prefixDirsPsr4[$prefix] = array_merge((array)$paths, self::$prefixDirsPsr4[$prefix]);
        } else {
            self::$prefixDirsPsr4[$prefix] = array_merge(self::$prefixDirsPsr4[$prefix], (array)$paths);
        }
    }

    /**
     * 添加老版psr-0格式命名空间
     * @param $prefix
     * @param $paths
     * @param bool $prepend
     */
    public static function addPsr0($prefix, $paths, $prepend = false)
    {
        if (!$prefix) {
            if ($prepend) {
                self::$fallbackDirsPsr0 = array_merge((array)$paths, self::$fallbackDirsPsr0);
            } else {
                self::$fallbackDirsPsr0 = array_merge(self::$fallbackDirsPsr0, (array)$paths);
            }
        }
        $first = $prefix[0];
        if (!isset(self::$prefixesPsr0[$first][$prefix])) {
            self::$prefixesPsr0[$first][$prefix] = (array)$paths;
        }
        if ($prepend) {
            self::$prefixesPsr0[$first][$prefix] = array_merge((array)$paths, self::$prefixesPsr0[$first][$prefix]);
        } else {
            self::$prefixesPsr0[$first][$prefix] = array_merge(self::$prefixesPsr0[$first][$prefix], (array)$paths);
        }
    }

    /**
     * 注册类库映射
     * @param $class
     * @param string $map
     */
    public static function addClassMap($class, $map = '')
    {
        if (is_array($class)) {
            self::$map = array_merge(self::$map, $class);
        } else {
            self::$map[$class] = $map;
        }
    }

    /**
     * 注册composer包自动加载
     * @param string $composerPath
     */
    public static function registerVendor($composerPath)
    {
        //老版psr0命名空间
        if (is_file($composerPath . 'autoload_classmap.php')) {
            $map = require_once $composerPath . "autoload_classmap.php";
            foreach ($map as $namespace => $path) {
                self::addPsr0($namespace, $path);
            }
        }

        //psr4格式命名空间
        if(is_file($composerPath . 'autoload_psr4.php')){
            $map = require $composerPath . 'autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                self::addPsr4($namespace, $path);
            }
        }

        //没有命名空间
        if (is_file($composerPath . 'autoload_classmap.php')) {
            $classMap = require $composerPath . 'autoload_classmap.php';
            if ($classMap) {
                self::addClassMap($classMap);
            }
        }
    }

    /**
     * 加载composer包文件
     * @return void
     */
    public static function loadVendor()
    {
        if (is_file(self::$composerPath . 'autoload_files.php')) {
            $autoloadFiles = require self::$composerPath . 'autoload_files.php';
            foreach ($autoloadFiles as $fileIdentifier => $file) {
                if (empty(self::$autoloadFiles[$fileIdentifier])) {
                    require_file($file);
                    self::$autoloadFiles[$fileIdentifier] = true;
                }
            }
        }
    }
}

/**
 * 隔离加载,出错继续加载
 * @param string $file
 * @return mixed
 */
function include_file($file)
{
    return include $file;
}

/**
 * 隔离加载,防止出错加载
 * @param string $file
 * @return mixed
 */
function require_file($file)
{
    return require $file;
}