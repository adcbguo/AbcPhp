<?php
/**
 * 框架帮助类
 * User: 郭冠常
 */

use core\facade\Response;

/**
 * 响应打印信息
 * @param $var
 * @return void
 */
function dump($var)
{
    Response::setDump($var);
}