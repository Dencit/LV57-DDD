<?php

namespace Modules\Base\Service;

use Modules\Base\Exception\BaseError;
use Modules\Base\Exception\Exception;

/**
 * notes: 领域层-业务基类
 */
class BaseService
{

    //订单序列号
    public static function SerialNumber($prefix = null, $useId = null)
    {

        $dateTime = self::udate("YmdHis");
        $nicoTime = self::udate("u");
        $randA    = rand(0, 9999);
        $randB    = rand(0, 9999);

        $prefix = empty($prefix) ? 100 : $prefix;
        $useId  = empty($useId) ? $nicoTime : $useId;
        $useId  = substr($useId, -4);

        //3位数渠道号 + 日月时分秒 + 随机1位数A + 用户id后4位 + 随机1位数B
        $num = $prefix . $dateTime . $randA . $useId . $randB;
        //dd($num);//

        return $num;
    }

    //生成微秒
    public static function udate($strFormat = 'u', $uTimeStamp = null)
    {
        // If the time wasn't provided then fill it in
        if (is_null($uTimeStamp)) {
            $uTimeStamp = microtime(true);
        }
        // Round the time down to the second
        $dtTimeStamp = floor($uTimeStamp);
        // Determine the millisecond value
        $intMilliseconds = round(($uTimeStamp - $dtTimeStamp) * 1000000);
        // Format the milliseconds as a 6 character string
        $strMilliseconds = str_pad($intMilliseconds, 6, '0', STR_PAD_LEFT);
        // Replace the milliseconds in the date format string
        // Then use the date function to process the rest of the string
        return date(preg_replace('`(?<!\\\\)u`', $strMilliseconds, $strFormat), $dtTimeStamp);
    }

    //json 格式检查
    public static function jsonCheck($jsonStr)
    {
        $data = json_decode($jsonStr, true);
        if (empty($data)) {
            Exception::app(BaseError::code("WRONG_JSON_FORMAT"), BaseError::msg("WRONG_JSON_FORMAT"), __METHOD__);
        }
        return $data;
    }

    //过滤url链接中的 根域名, 返回相对路径.
    public static function urlPathFilter($url, &$match = null)
    {
        $path = $url;
        preg_match("/^http.*(\.com|\.cn)(.*$)/", $url, $match);
        if (isset($match[2])) {
            $path = $match[2];
        }
        return $path;
    }

}