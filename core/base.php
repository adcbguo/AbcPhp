<?php
/**
 * 框架入口
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 18:06
 */
namespace core;
use Core\packet\Config;
use Core\packet\Db;
use Core\packet\Log;
use core\packet\App;
use core\packet\Page;

// 载入Loader类
require __DIR__ . '/library/Loader.php';

// 注册自动加载
Loader::register();

//注册异常处理
Error::register();

//绑定核心类到容器,方便后面直接调用
Container::getInstance()->bind([
    'app' => App::class,
    'config' => Config::class,
    'db' => Db::class,
    'log' => Log::class,
    'page' => Page::class
]);

//绑定核心类的静态代理,方便使用
Facade::bind([
    facade\App::class => App::class,
    facade\Config::class => Config::class,
    facade\Log::class => Log::class,
    facade\Db::class => Db::class,
    facade\Page::class => Page::class
]);

//加载composer包文件
Loader::loadVendor();