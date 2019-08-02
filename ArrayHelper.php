<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-10
 * Time: 16:52
 */

namespace helpers;

/**
 * 数组助手类
 * Class ArrayHelper
 * @package helpers
 */
class ArrayHelper
{
    /**
     * 二维数组排序 排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     * @param array $array 二维数组
     * @param $field string 排序字段
     * @param string $sort SORT_DESC 降序；SORT_ASC 升序
     * @return mixed
     */
    public static function array_sequence ($array, $field, $sort = 'SORT_DESC') {
        $arrSort = array();
        foreach ($array as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        array_multisort($arrSort[$field], constant($sort), $array);
        return $array;
    }

    /**
     * xml转数组
     * @param $xml string
     * @return mixed
     */
    public static function xmlToArray ($xml) {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }

    /**
     * 数组转xml
     * @param $arr array
     * @return string
     */
    public static function arrayToXml ($arr) {
        $xml = '';
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . self::arrayToXml($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        return $xml;
    }

    function arrayToXmlSetVersion ($arr) {
        $xml = '<?xml version="1.0" encoding="utf-8"?>';
        $xml .= self::arrayToXml($arr);
        return $xml;
    }
}
