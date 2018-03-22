<?php
/**
 * PHP7捕获异常
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 12:00
 */
namespace core\packet\exception;
use ErrorException;
use Throwable;
use ParseError;
use TypeError;
use ReflectionProperty;
class ThrowableError extends ErrorException
{
    /**
     * 把Throwable降级到ErrorException
     * @param Throwable $exception
     */
    public function __construct(Throwable $exception)
    {
        if ($exception instanceof ParseError) {
            $message = 'Parse error :' . $exception->getMessage();
            $severity = E_PARSE;
        } else if ($exception instanceof TypeError) {
            $message = 'Type error :' . $exception->getMessage();
            $severity = E_RECOVERABLE_ERROR;
        } else {
            $message = 'Fatal error :' . $exception->getMessage();
            $severity = E_ERROR;
        }
        parent::__construct($message, $exception->getCode(), $severity, $exception->getFile(), $exception->getLine());
        $this->setTrace($exception->getTrace());
    }

    /**
     * 使用属性反射把当前的跟踪信息发送到Exception类的trace属性
     * @param $trace
     */
    public function setTrace($trace)
    {
        $rflection = new ReflectionProperty('Exception', 'trace');
        $rflection->setAccessible(true);
        $rflection->setValue($this, $trace);
    }
}