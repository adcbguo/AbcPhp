<?php
/**
 * 项目入口
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 10:06
 */

namespace core;

//引用框架基础文件
require_once(__DIR__.'/../core/base.php');

//运行App
Container::get('core\\facade\\App')->run();