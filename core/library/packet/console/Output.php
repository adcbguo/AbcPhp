<?php

namespace core\packet\console;
class Output
{
    const VERBOSITY_QUIET = 16;
    const VERBOSITY_NORMAL = 32;
    const VERBOSITY_VERBOSE = 64;
    const VERBOSITY_VERY_VERBOSE = 128;
    const VERBOSITY_DEBUG = 256;

    const OUTPUT_NORMAL = 1;
    const OUTPUT_RAW = 2;
    const OUTPUT_PLAIN = 4;

    /**
     * 命令行输出构造函数
     * @param int|null $verbosity
     */
    public function __construct(?int $verbosity = self::VERBOSITY_NORMAL)
    {
        $this->verbosity = null === $verbosity ? self::VERBOSITY_NORMAL : $verbosity;
    }

    /**
     * 输出信息
     * @param string $messages
     * @param bool $newline
     * @param int $options
     */
    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {

    }

    /**
     * 输出信息并换行
     * @param $messages
     * @param int $options
     */
    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        $this->write($messages, true, $options);
    }
}