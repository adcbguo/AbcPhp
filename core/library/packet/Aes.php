<?php
/**
 * Aes加解密
 * User: 郭冠常
 * Date: 2018/3/9
 * Time: 11:16
 */
namespace app\common\help\base;
class Aes
{
    /**
     * 加密数据
     * @param string $str 明文
     * @param string $iv 加密的初始向量（IV的长度必须和Blocksize一样， 且加密和解密一定要用相同的IV）
     * @param string $key 密钥
     * @return string
     */
    public function aes128cbcEncrypt($str, $iv, $key)
    {
        $base = (mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$this->addPkcs7Padding($str,16) , MCRYPT_MODE_CBC, $iv));
        return $this->strToHex($base);
    }

    /**
     * 解密数据
     * @param String $encryptedText 二进制的密文
     * @param String $iv 加密时候的IV
     * @param String $key 密钥
     * @return String
     */
    public function aes128cbcDecrypt($encryptedText, $iv, $key)
    {
        $str = $this->hexToStr($encryptedText);
        return $this->stripPkcs7Padding(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $str, MCRYPT_MODE_CBC, $iv));
    }

    /**
     * pkcs7补码
     * @param string $string 明文
     * @param int $blocksize Blocksize , 以 byte 为单位
     * @return String
     */
    private function addPkcs7Padding($string, $blocksize = 32)
    {
        $len = strlen($string); //取得字符串长度
        $pad = $blocksize - ($len % $blocksize); //取得补码的长度
        $string .= str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
        return $string;
    }

    /**
     * 除去pkcs7 padding
     * @param string $string 解密后的结果
     * @return string
     */
    private function stripPkcs7Padding($string)
    {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }

    /**
     * 十六进制转字符串
     * @param $hex
     * @return string
     */
    private function hexToStr($hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;
    }

    /**
     * 字符串转十六进制
     * @param $string
     * @return string
     */
    private function strToHex($string)
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