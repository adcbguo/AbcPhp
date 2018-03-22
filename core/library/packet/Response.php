<?php
/**
 * 响应类
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 12:18
 */
namespace core\packet;
class Response
{
    public function send(){
        return '';
    }

    /**
     * 创建一个响应实例
     * @param string $content
     * @param string $type
     * @return Response
     */
    public function create($content = '', $type = 'json')
    {
        return $this;
    }
}