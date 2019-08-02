<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-10
 * Time: 17:05
 */

namespace helpers;

/**
 * 密码助手类
 * Class PassHelper
 * @package helpers
 */
class PassHelper
{
    /**
     * @param $str string 需要加密的字符串
     * @param $key string 秘钥
     * @return string 密文
     * 功能：对字符串进行加密处理
     * 参数一：需要加密的内容
     * 参数二：密钥
     */
    public static function passEncrypt ($str, $key) { //加密函数
        srand((double)microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr] . ($str[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode(self::passportKey($tmp, $key));
    }

    /**
     * @param $str string 密文
     * @param $key string 秘钥
     * @return string 明文
     * 功能：对字符串进行解密处理
     * 参数一：需要解密的密文
     * 参数二：密钥
     */
    public static function passDecrypt ($str, $key) { //解密函数
        $str = self::passportKey(base64_decode($str), $key);
        $tmp = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $md5 = $str[$i];
            $tmp .= $str[++$i] ^ $md5;
        }
        return $tmp;
    }

    /**
     * 加密解密辅助函数
     * @param $str string
     * @param $encrypt_key string
     * @return string
     */
    private static function passportKey ($str, $encrypt_key) {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $str[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }

    /**
     * 生成16位md5
     * @param $str string
     * @return bool|string
     */
    public static function short_md5($str) {
        return substr(md5($str), 8, 16);
    }
}
