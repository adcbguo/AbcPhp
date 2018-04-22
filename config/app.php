<?php
/**
 * app配置
 * User: 郭冠常
 * Date: 2018/3/10
 * Time: 10:31
 */
return [

    //是否开启调试
    'app_debug' => true,

    //是否开启错误跟踪
    'app_trace' => true,

    //默认的时区
    'default_timezone' => 'PRC',

    //是否开启多语言侦测
    'lang_switch_on' => false,

    //多语言侦测字段
    'lang_detection_field' => ['Accept-Language', 'Language'],

    //全局参数过滤
    'default_filter' => ['addslashes', 'htmlentities'],

    //默认语言
    'default_lang' => 'zh-cn',

    //默认处理异常的句柄
    'exception_handle' => '\\core\\packet\\exception\\Handle',

    //非调试模式下显示的异常信息
    'exception_message' => ['code' => 10010, 'msg' => '系统错误,请稍后再试!']
];