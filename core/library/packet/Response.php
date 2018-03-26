<?php

namespace core\packet;
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
    protected $data = [];

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
     * 状态码
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
        //发送内容
        $this->sendContent();
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
        $this->setType(empty($type) ? $this->type : $type);
        $this->content = json_encode($data, true);
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
     * 发送数据到客户端
     * @return void
     */
    private function sendContent()
    {
        echo $this->content;
    }
}