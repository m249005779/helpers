<?php
/**
 * Created by PhpStorm.
 * User: meng
 * Date: 2019-07-10
 * Time: 17:19
 */

namespace helpers;


class CurlHelper
{
    /**
     * @param string $url 请求地址
     * @param null $params 请求参数
     * @param array $headers 请求头
     * @return bool|string
     */
    public static function post ($url = '', $params = null, $headers = []) {
        if (empty($url) || empty($params)) return false;
        $cl = curl_init();
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($cl, CURLOPT_URL, $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cl, CURLOPT_POST, true);
        curl_setopt($cl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($cl, CURLOPT_TIMEOUT, 20);
        if (!empty($headers)) {
            if (is_array($headers)) {
                curl_setopt($cl, CURLOPT_HTTPHEADER, $headers);
            }
            if (is_string($headers)) {
                curl_setopt($cl, CURLOPT_HTTPHEADER, [$headers]);
            }
        }
        if (!empty($headers) && is_string($headers)) {
            curl_setopt($cl, CURLOPT_HTTPHEADER, [$headers]);
        }
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        curl_close($cl);
        if (isset($status['http_code'])) {
            return $content;
        }
        return false;
    }


    /**
     * @param string $url 请求地址
     * @param array $headers header
     * @return bool|string
     */
    function get ($url = '', $headers = []) {
        if (empty($url)) return false;
        $cl = curl_init();
        if (stripos($url, 'https://') !== FALSE) {
            curl_setopt($cl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($cl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($cl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($cl, CURLOPT_TIMEOUT, 10);
        curl_setopt($cl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($cl, CURLOPT_URL, $url);
        curl_setopt($cl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($headers) && is_array($headers)) {
            curl_setopt($cl, CURLOPT_HTTPHEADER, $headers);
        }
        if (!empty($headers) && is_string($headers)) {
            curl_setopt($cl, CURLOPT_HTTPHEADER, [$headers]);
        }
        $content = curl_exec($cl);
        $status = curl_getinfo($cl);
        curl_close($cl);
        if (isset($status['http_code'])) {
            return $content;
        }
        return false;
    }
}
