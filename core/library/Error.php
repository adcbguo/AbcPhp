<?php
/**
 * 错误类
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 19:50
 */
namespace core;
use core\facade\Config;
use core\packet\exception\ErrorException;
use core\packet\exception\Handle;
use Closure;
use core\packet\exception\ThrowableError;
use Exception;
use Throwable;
class Error
{
    /**
     * 注册异常和错误处理类
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__,'appShutdown']);
    }

    /**
     * 错误处理
     * @param int $errno 错误编号
     * @param string $errstr 详细错误信息
     * @param string $errfile 出错的文件
     * @param int $errline 出错行号
     * @throws ErrorException
     */
    public static function appError($errno, $errstr, $errfile = '', $errline = 0)
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline);
        if (error_reporting() & $errno) {
            throw $exception;
        } else {
            //记录错误
            self::getExceptionHandler()->report($exception);
        }
    }

    /**
     * 异常处理
     * @param Exception|Throwable $exception
     */
    public static function appException($exception)
    {
        //php7版本降级解决
        if (!$exception instanceof Exception) {
            $exception = new ThrowableError($exception);
        }
        //记录错误
        self::getExceptionHandler()->report($exception);
        //处理异常跟踪
        self::getExceptionHandler()->render($exception)->send();
    }

    /**
     * 程序终止处理
     * @return void
     */
    public static function appShutdown()
    {

    }

    /**
     * 获取异常处理实例
     * @return Handle
     */
    public static function getExceptionHandler()
    {
        static $handle;
        if (!$handle) {
            $class = Container::get('core\\packet\\Config')->get('exception_handle');
            if ($class && is_string($class) && class_exists($class) && is_subclass_of($class, '\\core\\packet\\exception\\Handle')) {
                $handle = new $class;
            } else {
                $handle = new Handle();
                if ($class instanceof Closure) {
                    $handle->setRender($class);
                }
            }
        }
        return $handle;
    }
}