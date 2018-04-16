<?php

namespace core\statics;

/**
 * 字符串实现类
 * @package core\packet
 */
class Str
{
    /**
     * 处理错误跟踪信息
     * @param $trace
     * @return array
     */
    public static function parseTrace($trace)
    {
        $parseTrace = '%FILE%%LINE%%CLASS%%TYPE%%FUNCTION%';
        $traceArr = [];
        foreach ($trace as $tr) {
            array_push($traceArr, str_replace(
                ['%FILE%', '%CLASS%', '%TYPE%', '%FUNCTION%', '%LINE%'],
                [
                    !isset($tr['file']) ? '' : "[{$tr['file']}]",
                    !isset($tr['class']) ? '' : "[{$tr['class']}",
                    !isset($tr['type']) ? '' : $tr['type'],
                    isset($tr['class']) ? "{$tr['function']}]" : "[{$tr['function']}]",
                    !isset($tr['line']) ? '' : "[{$tr['line']}]",
                ],
                $parseTrace));
        }
        return $traceArr;
    }
}