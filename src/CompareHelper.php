<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-10
 * Time: 16:55
 */

namespace helpers;

/**
 * 比较助手类
 * Class CompareHelper
 * @package helpers
 */
class CompareHelper
{
    /**
     * 判断是否为合法json数据
     * @param $json string
     * @return bool
     */
    public static function isRealJson ($json) {
        if (!is_string($json) || is_array($json) || is_null($json) || empty($json) || !isset($json)) {
            return false;
        }
        json_decode($json);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * 验证是否大于0的正整数，用户验证ID
     * @param $param string
     * @return bool
     */
    public static function isInt ($param) {
        if (is_numeric($param) && ($param + 0) > 0 && intval($param) > 0) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否正确的电话号码
     * @param $value string 电话号码
     * @return bool
     */
    public static function isMobile ($value) {
        $rule = '^1(3|4|5|6|7|8|9)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        return $result ? true : false;
    }

    /**
     * 判断是否为微信浏览器
     * @return bool
     */
    public static function isWechat () {
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return true;
        }
        return false;
    }

    /**
     * 判断是否为时间戳
     * @param $timestamp string|int
     * @return bool
     */
    public static function isTimestamp ($timestamp) {
        if (!self::isInt($timestamp)) {
            return false;
        }
        if (strtotime(date('Y-m-d H:i:s', $timestamp)) == $timestamp) {
            return true;
        }
        return false;
    }

    /**
     * 验证是否为正确的身份证号
     * @param $vStr string 身份证号码
     * @return bool
     */
    public static function isCreditNo ($vStr) {
        $vCity = array(
            '11', '12', '13', '14', '15', '21', '22',
            '23', '31', '32', '33', '34', '35', '36',
            '37', '41', '42', '43', '44', '45', '46',
            '50', '51', '52', '53', '54', '61', '62',
            '63', '64', '65', '71', '81', '82', '91'
        );
        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);
        if ($vLength == 18) {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }
        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18) {
            $vSum = 0;
            for ($i = 17; $i >= 0; $i--) {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr, 11));
            }
            if ($vSum % 11 != 1) return false;
        }
        return true;
    }


    /**
     * 验证小数不能超过几位, 不能有两个小数点
     * @param $num float|string
     * @param int $len 小数点后面的长度
     * @return bool
     */
    public static function floatLength ($num, $len = 2) {
        $count = 0;
        $temp = explode('.', $num);
        if (count($temp) > $len) {
            return false;
        }
        if (sizeof($temp) > 1) {
            $decimal = end($temp);
            $count = strlen($decimal);
        }
        if ($count > $len) {
            return false;
        }
        return true;
    }

    /**
     * 验证两个区间是否有交集 相同算入内
     * @param string $beginTime1 开始1
     * @param string $endTime1 结束1
     * @param string $beginTime2 开始2
     * @param string $endTime2 结束2
     * @return bool
     */
    public static function isCrossEq ($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '') {
        $status = $beginTime2 - $beginTime1;
        if ($status > 0) {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 > 0) {
                return false;
            } else {
                return true;
            }
        } elseif ($status < 0) {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 >= 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * @param string $beginTime1 开始1
     * @param string $endTime1 结束1
     * @param string $beginTime2 开始2
     * @param string $endTime2 结束2
     * @return bool
     */
    public static function is_cross ($beginTime1 = '', $endTime1 = '', $beginTime2 = '', $endTime2 = '') {
        $status = $beginTime2 - $beginTime1;
        if ($status > 0) {
            $status2 = $beginTime2 - $endTime1;
            if ($status2 > 0) {
                return false;
            } elseif ($status2 < 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($status < 0) {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 > 0) {
                return true;
            } else if ($status2 < 0) {
                return false;
            } else {
                return false;
            }
        } else {
            $status2 = $endTime2 - $beginTime1;
            if ($status2 == 0) {
                return false;
            } else {
                return true;
            }
        }
    }
}
