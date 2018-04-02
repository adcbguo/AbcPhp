<?php
/**
 * 框架入口
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 18:06
 */

namespace core;

use core\packet\Config;
use core\packet\Db;
use core\packet\Log;
use core\packet\App;
use core\packet\Page;
use core\packet\Response;

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
    'page' => Page::class,
    'response' => Response::class
]);

//绑定核心类的静态代理,方便使用
Facade::bind([
    facade\App::class => App::class,
    facade\Config::class => Config::class,
    facade\Log::class => Log::class,
    facade\Db::class => Db::class,
    facade\Page::class => Page::class,
    facade\Response::class => Response::class,
]);

//加载composer包文件
Loader::loadVendor();