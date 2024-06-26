<?php

namespace Extend\Util;

/**
 * notes: 地理信息处理类
 * @author 陈鸿扬 | @date 2021/3/16 11:08
 */
class GeoHelper
{

    /**
     * notes: 计算两点地理坐标之间的距离 - 已经调较到和ES计算结果相等
     * @author 陈鸿扬 | @date 2021/1/5 12:48
     * @param $longitude1 起点经度
     * @param $latitude1 起点纬度
     * @param $longitude2 终点经度
     * @param $latitude2 终点纬度
     * @param Int $unit 单位 1:米 2:公里
     * @param Int $decimal 精度 保留小数位数
     * @return float
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit = 2, $decimal = 2)
    {
        $EARTH_RADIUS = 6371.0069757804; //地球半径系数
        $PI           = 3.14159265358979323846;
        $radLat1      = $latitude1 * $PI / 180.0;
        $radLat2      = $latitude2 * $PI / 180.0;
        $radLng1      = $longitude1 * $PI / 180.0;
        $radLng2      = $longitude2 * $PI / 180.0;
        $a            = $radLat1 - $radLat2;
        $b            = $radLng1 - $radLng2;
        $distance     = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $distance     = $distance * $EARTH_RADIUS * 1000;
        if ($unit == 2) {
            $distance = $distance / 1000;
        }
        return round($distance, $decimal);
    }

}