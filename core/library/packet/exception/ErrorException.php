<?php
/**
 * 错误异常处理
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 19:53
 */
namespace core\packet\exception;
use core\packet\Exception;
class ErrorException extends Exception
{
    protected $severity;

    /**
     * 错误异常构造函数
     * @param int $severity 错误级别
     * @param string $message 错误信息
     * @param string $errfile 错误文件
     * @param int $errline 错误行数
     */
    public function __construct($severity, $errstr, $errfile, $errline)
    {
        $this->severity = $severity;
        $this->message = $errstr;
        $this->file = $errfile;
        $this->line = $errline;
        $this->code = 0;
    }

    /**
     * 获取错误级别
     * @return int
     */
    final public function getSeverity()
    {
        return $this->severity;
    }
}