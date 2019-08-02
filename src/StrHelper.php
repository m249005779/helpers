<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-10
 * Time: 16:46
 */

namespace helpers;

/**
 * 字符串助手类
 * Class StrHelper
 * @package helpers
 */
class StrHelper
{
    protected static $snakeCache = [];

    protected static $camelCache = [];

    protected static $studlyCache = [];

    /**
     * 阿拉伯数字转中文
     * @param $num string 阿拉伯数字
     * @return mixed|string 中文数字
     */
    public static function numToWord ($num) {
        $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
        $chiUni = array('', '十', '百', '千', '万', '亿', '十', '百', '千');
        $num_str = (string)$num;
        $count = strlen($num_str);
        $last_flag = true; //上一个 是否为0
        $zero_flag = true; //是否第一个
        $temp_num = null; //临时数字
        $chiStr = '';//拼接结果
        if ($count == 2) {//两位数
            $temp_num = $num_str[0];
            $chiStr = $temp_num == 1 ? $chiUni[1] : $chiNum[$temp_num] . $chiUni[1];
            $temp_num = $num_str[1];
            $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
        } else if ($count > 2) {
            $index = 0;
            for ($i = $count - 1; $i >= 0; $i--) {
                $temp_num = $num_str[$i];
                if ($temp_num == 0) {
                    if (!$zero_flag && !$last_flag) {
                        $chiStr = $chiNum[$temp_num] . $chiStr;
                        $last_flag = true;
                    }
                } else {
                    $chiStr = $chiNum[$temp_num] . $chiUni[$index % 9] . $chiStr;
                    $zero_flag = false;
                    $last_flag = false;
                }
                $index++;
            }
        } else {
            $chiStr = $chiNum[$num_str[0]];
        }
        return $chiStr;
    }

    /**
     * 生成36 唯一ID
     * @param string $namespace
     * @return string
     */
    public static function create_guid ($namespace = '') {
        static $guid = '';
        $uid = uniqid("", true);
        $data = $namespace;
        $data .= $_SERVER['REQUEST_TIME'];
        $data .= $_SERVER['HTTP_USER_AGENT'];
        $data .= isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : '';
        $data .= isset($_SERVER['LOCAL_PORT']) ? $_SERVER['LOCAL_PORT'] : '';
        $data .= $_SERVER['REMOTE_ADDR'];
        $data .= $_SERVER['REMOTE_PORT'];
        $data .= microtime();
        $hash = strtolower(hash('ripemd128', $uid . $guid . md5($data)));
        $guid = substr($hash, 0, 8) .
            '-' .
            substr($hash, 8, 4) .
            '-' .
            substr($hash, 12, 4) .
            '-' .
            substr($hash, 16, 4) .
            '-' .
            substr($hash, 20, 12);
        return $guid;
    }

    /**
     * 检查字符串中是否包含某些字符串
     * @param string $haystack
     * @param string|array $needles
     * @return bool
     */
    public static function contains ($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * 检查字符串是否以某些字符串结尾
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function endsWith ($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if ((string)$needle === static::substr($haystack, -static::length($needle))) {
                return true;
            }
        }
        return false;
    }

    /**
     * 检查字符串是否以某些字符串开头
     * @param  string $haystack
     * @param  string|array $needles
     * @return bool
     */
    public static function startsWith ($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && mb_strpos($haystack, $needle) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取指定长度的随机字母数字组合的字符串
     * @param  int $length
     * @return string
     */
    public static function random ($length = 16) {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return static::substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * 字符串转小写
     * @param  string $value
     * @return string
     */
    public static function lower ($value) {
        return mb_strtolower($value, 'UTF-8');
    }

    /**
     * 字符串转大写
     * @param  string $value
     * @return string
     */
    public static function upper ($value) {
        return mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 获取字符串的长度
     * @param  string $value
     * @return int
     */
    public static function length ($value) {
        return mb_strlen($value);
    }

    /**
     * 截取字符串
     * @param  string $string
     * @param  int $start
     * @param  int|null $length
     * @return string
     */
    public static function substr ($string, $start, $length = null) {
        return mb_substr($string, $start, $length, 'UTF-8');
    }

    /**
     * 驼峰转下划线
     * @param  string $value
     * @param  string $delimiter
     * @return string
     */
    public static function snake ($value, $delimiter = '_') {
        $key = $value;
        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);
            $value = static::lower(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value));
        }
        return static::$snakeCache[$key][$delimiter] = $value;
    }

    /**
     * 下划线转驼峰(首字母小写)
     * @param  string $value
     * @return string
     */
    public static function camel ($value) {
        if (isset(static::$camelCache[$value])) {
            return static::$camelCache[$value];
        }
        return static::$camelCache[$value] = lcfirst(static::studly($value));
    }

    /**
     * 下划线转驼峰(首字母大写)
     * @param  string $value
     * @return string
     */
    public static function studly ($value) {
        $key = $value;
        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return static::$studlyCache[$key] = str_replace(' ', '', $value);
    }

    /**
     * 转为首字母大写的标题格式
     * @param  string $value
     * @return string
     */
    public static function title ($value) {
        return mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
    }
}
