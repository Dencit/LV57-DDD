<?php
namespace Extend\ArtisanTest\Base\Cache;

use Illuminate\Support\Facades\Redis;

/**
 * notes:
 * @author 陈鸿扬 | @date 2020/12/10 13:48
 * Class CacheRedis
 */
class CacheRedis
{
    protected static $Redis;
    protected $sign;

    public function __construct($table_num=0)
    {
        $this->sign = $this->hashSign(0);
        self::$Redis = Redis::class;

        if( $table_num==0 ){
            Redis::select(0);
        }else{
            //控制器数据缓存 统一放 第2个库
            Redis::select($table_num);
        }

        return self::$Redis;
    }

    public function getData($key){
        if(request()->get('_time')==1){
            return false;
        }else{
            $key=config("database.redis.default.prefix").$key.':'.$this->sign;

            return self::$Redis::get($key);
        }
    }
    public function getDataByMineKey($key,$mine=null){
        if(request()->get('_time')==1){
            return false;
        }else{
            $key=config("database.redis.default.prefix").$key.':'.$this->mineKey($mine).$this->sign;

            return self::$Redis::get($key);
        }
    }


    public function setData($key,$value,$expire=null){
        $key=config("database.redis.default.prefix").$key.':'.$this->sign;

        if( $expire ){ return self::$Redis::set($key,$value,$expire); }
        return self::$Redis::set($key,$value);
    }
    public function setDataByMineKey($key,$value,$mine=null,$expire=null){
        $key=config("database.redis.default.prefix").$key.':'.$this->mineKey($mine).$this->sign;

        if( $expire ){ return self::$Redis::set($key,$value,$expire); }
        return self::$Redis::set($key,$value);
    }


    public function delData($key){
        $key=config("database.redis.default.prefix").$key.':'.$this->sign;
        return self::$Redis::del($key);
    }
    public function delDataByMineKey($key,$mine=null){
        $key=config("database.redis.default.prefix").$key.':'.$this->mineKey($mine).$this->sign;
        return self::$Redis::del($key);
    }

    public function mineKey($key=null){
        if( $key!=null ){
            return 'mine_key_'.$key;
        }else{
            return '';
        }
    }
    public function hashSign($type=null){
        $normalData =  request()->query();
        unset($normalData['_time']); //排除实时字段
        $queryStr=""; //待拼接字符串
        ksort($normalData);
        foreach ($normalData as $k=>$v){
            if( !empty($v) ){ $queryStr.= $k."=".$v."&";}
        }
        $queryStr = trim($queryStr,"&");
        if($type){
            $newShaSign = hash("sha256", $queryStr);
        }else{
            $newShaSign = $queryStr;
        }
        return $newShaSign;
    }

}