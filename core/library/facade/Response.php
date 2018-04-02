<?php
namespace core\facade;

use core\Facade;

/**
 * 响应工厂类
 * User: 郭冠常
 * @package core\facade
 * @method \core\packet\Response setDate(array $data, string $type = 'application/json') static 设置响应内容
 * @method \core\packet\Response send() static 发送数据到客户端
 * @method \core\packet\Response setType($type, $charset = '') static 设置返回类型
 * @method \core\packet\Response setHeader($name, $value = null) static 设置header头
 * @method array|string|null getHeader($name = '') static 获取header头
 * @method string|null getContent() static 获取发送到客户端内容
 * @method \core\packet\Response setTrace($trace) static 设置跟踪响应
 * @method void setDump($var) static 发送调试信息
 */
class Response extends Facade
{

}