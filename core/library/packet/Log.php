<?php
namespace core\packet;
/**
 * 日志类
 * 命令行日志实时写入文件
 * 请求日志写入数据库
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 19:19
 */
class Log
{
    /**
     * 记录错误信息
     * @param string $msg 错误信息
     * @param string $level 错误级别
     * @return void
     */
    public function record($msg, $level = 'info')
    {

    }
}