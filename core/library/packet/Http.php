<?php

namespace core\packet;
use core\packet\http\Curl;
use core\packet\http\FsockOpen;

/**
 * 对外请求类
 * User: 郭冠常
 */
class Http
{
    /**
     * 请求类型
     * @var string
     */
    protected $method = 'GET';

    /**
     * 请求方式
     * @var string
     */
    protected static $mode = 'Curl';

    /**
     * 请求超时时间
     * @var int
     */
    protected $timeOut = 30;

    /**
     * 请求是否使用证书
     * @var bool
     */
    protected $useCert = false;

    /**
     * 请求公钥证书文件地址
     * @var string
     */
    protected $certPath;

    /**
     * 请求私钥证书文件地址
     * @var string
     */
    protected $keyPath;

    /**
     * 是否验证证书
     * @var bool
     */
    protected $verifyHost = false;

    /**
     * CA根证书
     * @var string
     */
    protected $caInfo;

    /**
     * 设置header头
     * @var array
     */
    protected $header = [];

    /**
     * 请求资源对象
     * @var resource
     */
    protected $http;

    /**
     * 请求地址
     * @var string
     */
    protected $url;

    /**
     * 响应内容
     * @var string
     */
    protected $content;

    /**
     * 请求架构函数
     * @param string $url
     */
    public function __construct($url = '')
    {
        $this->url = empty($url) ? $this->url : $url;
    }

    /**
     * 创建请求对象
     * @param $url
     * @param string $mode
     * @return static|Curl|FsockOpen
     */
    public static function create($url, $mode = '')
    {
        $mode = empty($mode) ? self::$mode : strtolower($mode);
        $class = false !== strpos($mode, '\\') ? $mode : '\\core\\packet\\http\\' . ucfirst($mode);
        if (class_exists($class)) return new $class($url);
        else return new static($url);
    }

    /**
     * 设置header头
     * @param string|array $name
     * @param null $value
     */
    public function setHeader($name, $value = null)
    {
        if (is_array($name)) $this->header = array_merge($this->header, $name);
        else $this->header[$name] = $value;
    }

    /**
     * 执行请求
     * @return $this
     */
    public function exec()
    {
        return $this;
    }

    /**
     * 获取请求响应内容
     * @param string $type
     * @return string
     */
    public function getResponseContent($type = 'json')
    {
        if ($type == 'json') {
            return json_decode($this->content, true);
        } else {
            return $this->content;
        }
    }

    /**
     * 获取响应头信息
     * @param string|null $name
     * @return array|string|null
     */
    public function getResponseHeader($name = null)
    {
        return [];
    }
}