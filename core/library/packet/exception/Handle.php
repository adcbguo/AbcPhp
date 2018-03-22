<?php
/**
 * 错误异常处理类
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 20:05
 */
namespace core\packet\exception;
use core\Container;
use core\packet\Response;
use Exception;
use Closure;

class Handle
{
    //闭包
    protected $render;

    /**
     * 忽略的类
     * @var array
     */
    protected $ignoreReport = [
        '\\core\\packet\\exception\\HttpException'
    ];

    /**
     * 记录异常到日志
     * @param Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (!$this->isIgnoreReport($exception)) {
            if (Container::get('app')->isDebug()) {
                $data = [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ];
                $log = "[{$data['code']}][{$data['message']}][{$data['file']}][{$data['line']}]";
            } else {
                $data = [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode()
                ];
                $log = "[{$data['code']}][{$data['message']}]";
            }
            Container::get('log')->record($log, 'error');
        }
    }

    /**
     * 配置闭包
     * @param $render
     * @return void
     */
    public function setRender($render)
    {
        $this->render = $render;
    }

    /**
     * 处理异常跟踪
     * @param $exception
     * @return Response
     */
    public function render($exception)
    {
        if ($this->render && $this->render instanceof Closure) {
            $result = call_user_func_array($this->render, [$exception]);
            if ($result) return $result;
        }
        if ($exception instanceof HttpException) {
            return $this->renderHttpException($exception);
        } else {
            return $this->convertExceptionToResponse($exception);
        }
    }

    /**
     * 处理Http异常
     * @param $exception
     * @return Response
     */
    public function renderHttpException($exception)
    {
        return $this->convertExceptionToResponse($exception);
    }

    /**
     * 奖异常打印到页面
     * @param $exception
     * @return Response
     */
    public function convertExceptionToResponse($exception)
    {
        return Response::create();
    }

    /**
     * 是否忽略处理
     * @param Exception $exception
     * @return bool
     */
    public function isIgnoreReport(Exception $exception){
        foreach ($this->ignoreReport as $class) {
            if($exception instanceof $class){
                return false;
            }
        }
        return false;
    }
}