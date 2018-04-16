<?php
namespace core\packet\crypto;
/**
 * 16机制转换类
 * @package core\packet\crypto
 */
class Hex
{
    /**
     * 十六进制转字符串
     * @param string $hex
     * @return string
     */
    public static function hexToStr($hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;
    }

    /**
     * 字符串转十六进制
     * @param string $string
     * @return string
     */
    public static function strToHex($string)
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++) {
            $tmp = dechex(ord($string[$i]));
            $hex .= strlen($tmp) == 1 ? "0" . $tmp : $tmp;
        }
        $hex = strtoupper($hex);
        return $hex;
    }
}