<?php

namespace core\packet\http;
/**
 * fsockopen请求
 * User: 郭冠常
 */
class FsockOpen
{
    /**
     * Get请求
     * @param $field
     * @return FsockOpen
     */
    public function get($field = [])
    {
        return $this;
    }

    /**
     * Post请求
     * @param array $data
     * @return $this
     */
    public function post($data = [])
    {
        return $this;
    }
}