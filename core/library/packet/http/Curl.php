<?php

namespace core\packet\http;

use core\packet\Http;

/**
 * Curl请求
 * User: 郭冠常
 */
class Curl extends Http
{
    /**
     * Get请求
     * @param $field
     * @return Http
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