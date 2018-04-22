<?php

namespace core\packet\console;
use core\packet\Console;

class Command
{
    /**
     * 控制台对象
     * @var Console
     */
    private $console;

    /**
     * 控制台输入对象
     * @var Input
     */
    private $input;

    /**
     * 控制台输出对象
     * @var Output
     */
    private $output;

    /**
     * 命令构造函数
     * @param null|string $cmd
     */
    public function __construct($cmd = null)
    {

    }

    /**
     * 设置控制台
     * @param Console $console
     */
    public function setConsole(Console $console)
    {
        $this->console = $console;
    }

    /**
     * 获取控制台对象
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     *
     * @param Input $input
     * @param Output $output
     */
    public function run(Input $input, Output $output)
    {
        $this->input = $input;
        $this->output = $output;
    }
}