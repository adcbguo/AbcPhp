<?php

namespace core\packet;
use core\statics\Str;

/**
 * 响应类
 * User: 郭冠常
 * @package core\packet
 */
class Response
{
    /**
     * 响应数据
     * @var array
     */
    protected $data = ['code' => 200, 'error' => ''];

    /**
     * 响应类型
     * @var string
     */
    protected $type = 'application/json';

    /**
     * 字符集
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * 网页状态码
     * @var integer
     */
    protected $code = 200;

    /**
     * header参数
     * @var array
     */
    protected $header = [];

    /**
     * 输出内容
     * @var string
     */
    protected $content = null;

    /**
     * 实例化响应类
     * @param array $data
     * @param string $type
     * @param array $header
     */
    public function __construct($data = [], $type = 'application/json', array $header = [])
    {
        $this->setDate($data, $type);
        $this->header = array_merge($this->header, $header);
    }

    /**
     * 返回客户端内容
     * @return Response
     */
    public function send()
    {
        //发送header头信息
        if (!headers_sent() && !empty($this->header)) {
            http_response_code($this->code);
            foreach ($this->header as $name => $value) {
                header($name . (!is_null($value) ? ':' . $value : ''));
            }
        }

        //设置打印内容
        $this->content = json_encode($this->data, true);

        //发送内容
        $this->print();

        //提高页面响应
        if (function_exists('fastcgi_finish_request')) fastcgi_finish_request();

        return $this;
    }

    /**
     * 设置类型
     * @param string $type
     * @param string $charset
     * @return Response
     */
    public function setType($type, $charset = '')
    {
        $charset = empty($charset) ? $this->charset : $charset;
        $this->header['Content-Type'] = $type . '; charset=' . $charset;
        return $this;
    }

    /**
     * 设置header数据
     * @param string|array $name
     * @param null $value
     * @return Response
     */
    public function setHeader($name, $value = null)
    {
        if (is_array($name)) $this->header = array_merge($this->header, $name);
        else $this->header[$name] = $value;
        return $this;
    }

    /**
     * 获取header信息
     * @param string $name
     * @return array|string|null
     */
    public function getHeader($name = '')
    {
        if (!empty($name)) return isset($this->header[$name]) ? $this->header[$name] : null;
        else return $this->header;
    }

    /**
     * 设置输出内容
     * @param array $data
     * @param string $type
     * @return Response
     */
    public function setDate($data = [], $type = '')
    {
        $this->data = array_merge($this->data, $data);
        $this->setType(empty($type) ? $this->type : $type);
        return $this;
    }

    /**
     * 获取数据
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 打印输出
     * @return void
     */
    private function print()
    {
        echo $this->content;
    }

    /**
     * 组装错误跟踪信息
     * @param array $trace
     * @return $this
     */
    public function setTrace($trace, $file, $line)
    {
        $this->data['file'] = $file;
        $this->data['line'] = $line;
        $this->data['trace'] = Str::parseTrace($trace);
        return $this;
    }

    /**
     * 打印调试信息
     * @param $var
     * @return void
     */
    public function setDump($var)
    {
        isset($this->data['dump']) ?: $this->data['dump'] = [];
        //is_string($var) ?: $var = var_export($var, true);
        array_push($this->data['dump'], $var);
    }
}