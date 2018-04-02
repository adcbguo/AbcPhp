<?php
/**
 * 框架帮助类
 * User: 郭冠常
 */

/**
 * 返回打印信息
 * @param $var
 * @return void
 */
function dump($var)
{
    \core\facade\Response::setDump($var);
}