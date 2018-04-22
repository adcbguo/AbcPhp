<?php

namespace core\packet;
use core\packet\console\Command;

class Console
{
    /**
     * 执行的命令
     * @var array
     */
    private $commands = [];

    /**
     * 控制台构造函数
     * @param string $name
     * @param string $version
     * @param null $user
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN', $user = null)
    {

    }

    /**
     * 初始化控制台
     * @param bool $run 是否直接执行命令
     * @return int|Console
     */
    public function init($run = true)
    {
        if ($run) {
            return $this->run();
        } else {
            return $this;
        }
    }

    /**
     * 执行当前命令
     * @return int
     */
    public function run()
    {
        return 0;
    }


    public function add(Command $cmd)
    {

    }
}