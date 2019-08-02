<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-06
 * Time: 15:49
 */

namespace helpers;


class TimeHelper
{
    /**
     * 获取今天的0点到今天晚上的23:59:59
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function today ($timeStamp = true) {
        $time = time();
        $start = date('Y-m-d', $time);
        $end = $start . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start . ' 00:00:00', $end];
    }

    /**
     * 昨日开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function yestoday ($timeStamp = true) {
        $start = date('Y-m-d', strtotime('-1 days'));
        $end = $start . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start . ' 00:00:00', $end];
    }

    /**
     * 本周开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function week ($timeStamp = true) {
        $start = date('Y-m-d', strtotime('this week Monday')) . ' 00:00:00';
        $end = date('Y-m-d', strtotime('this week Sunday')) . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 上周开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function lastWeek ($timeStamp = true) {
        $start = date('Y-m-d', strtotime('last week Monday')) . ' 00:00:00';
        $end = date('Y-m-d', strtotime('last week Sunday')) . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 本月开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function month ($timeStamp = true) {
        $start = date('Y-m-01') . ' 00:00:00';
        $end = date('Y-m-t') . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 上月开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function lastMonth ($timeStamp = true) {
        $start = date('Y-m-01', strtotime('-1 months')) . ' 00:00:00';
        $end = date('Y-m-t', strtotime('-1 months')) . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 今年开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function year ($timeStamp = true) {
        $start = date('Y-01-01 00:00:00');
        $end = date('Y-12-31 23:59:59');
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 去年开始和结束的时间
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function lastYear ($timeStamp = true) {
        $start = date('Y-01-01 00:00:00', strtotime('last year'));
        $end = date('Y-12-31 23:59:59', strtotime('last year'));
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 获取季度开始和结束时间
     * @param bool $timeStamp 是否返回时间戳
     * @param null|int $season 第几季度,默认为本季度
     * @return array
     */
    public static function quarter ($timeStamp = true, $season = null) {
        $season = intval($season);
        if (empty($season)) {
            $season = ceil((date('n')) / 3);
        }
        $start = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y')));
        $end = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y')));
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 获取几天之前和现在的时间
     * @param $days int 几天之前
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function dayToNow ($days, $timeStamp = true) {
        $start = date('Y-m-d', self::daysAgoStamp($days)) . ' 00:00:00';
        if ($timeStamp) {
            return [strtotime($start), time()];
        }
        $end = date('Y-m-d H:i:s', time());
        return [$start, $end];
    }

    /**
     * 获取几天之前和昨天结束的时间
     * @param $days int 几天之前
     * @param bool $timeStamp 是否返回时间戳
     * @return array
     */
    public static function dayToYestoday ($days, $timeStamp = true) {
        $start = $start = date('Y-m-d', self::daysAgoStamp($days)) . ' 00:00:00';
        $end = date('Y-m-d', strtotime('-1 days')) . ' 23:59:59';
        if ($timeStamp) {
            return [strtotime($start), strtotime($end)];
        }
        return [$start, $end];
    }

    /**
     * 获取几天之前的时间
     * @param $days int 几天
     * @param bool $start 是否是开始时间
     * @param bool $timeStamp 是否返回时间戳
     * @return string
     */
    public static function daysAgo ($days, $start = false, $timeStamp = true) {
        $time = self::daysAgoStamp($days);
        if ($start == false) {
            if ($timeStamp) {
                return $time;
            }
            return date('Y-m-d H:i:s', $time);
        } else {
            $time = date('Y-m-d', $time) . ' 00:00:00';
            if ($timeStamp) {
                return strtotime($time);
            }
            return $time;
        }
    }

    /**
     * 获取几天之后的时间
     * @param $days int 几天
     * @param bool $start 是否是开始时间
     * @param bool $timeStamp 是否返回时间戳
     * @return string
     */
    public static function daysAfter ($days, $start = false, $timeStamp = true) {
        $time = strtotime('+' . $days . ' days');
        if ($start) {
            if ($timeStamp) {
                return strtotime(date('Y-m-d', $time));
            }
            return date('Y-m-d', $time) . ' 00:00:00';
        } else {
            if ($timeStamp) {
                return $time;
            }
            return date('Y-m-d H:i:s', $time);
        }
    }

    /**
     * 获取几天的秒数
     * @param int $days 天数
     * @return float|int
     */
    public static function daysToSecond ($days = 1) {
        return $days * 86400;
    }

    /**
     * 把周转化为秒数
     * @param int $weeks 周数
     * @return float|int
     */
    public static function weekToSecond ($weeks = 1) {
        return $weeks * 604800;
    }

    /**
     * 返回一个未来的时间到现在的秒数
     * @param $datetime string 时间
     * @return false|int
     */
    public static function datetimeToNow ($datetime) {
        $time = time();
        $expri = strtotime($datetime) - $time;
        if ($expri <= 0) {
            return 0;
        }
        return $expri;
    }

    /**
     * @param $day1 string 2012-12-24 时间
     * @param string $day2
     * @return int
     */
    public static function daysBetween ($day1, $day2 = '') {
        $day2 = empty($day2) ? date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']) : $day2;
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return intval(($second1 - $second2) / 86400);
    }

    private static function daysAgoStamp ($days) {
        return strtotime('-' . $days . ' days');
    }
}
