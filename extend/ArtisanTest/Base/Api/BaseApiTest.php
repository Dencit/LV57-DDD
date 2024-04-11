<?php
namespace Extend\ArtisanTest\Base\Api;

use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\TestResult;
use Extend\ArtisanTest\Base\Cache\CacheRedis;
use Tests\TestCase;

/**
 * notes:
 * @author 陈鸿扬 | @date 2020/12/10 13:48
 * Class BaseApiTest
 */
abstract class BaseApiTest extends TestCase
{
    protected $baseHost;

    //url转换
    public function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    //生成签名
    public function makeSignData($data){
        $base = [
            'appkey'=>'appkey',
            'appsecret'=>'appsecret',
            'timestamp'=> time(),
            'nonce'=> bin2hex(random_bytes(16))
        ];
        ksort($base);
        $params = http_build_query($base, null, '&', PHP_QUERY_RFC3986);
        $base['sign'] = md5($params);
        $data = array_merge($data,$base);
        return $data;
    }
    //获取token
    public function getUserToken(){
        $url = $this->baseHost.'/user/login';
        $data=[
            'source'=>1,
            'countryCode'=>'+86',
            'mobile'=>'18500010002',
            'password'=>'123456'
        ];
        $header=[];
        $header = $this->makeSignData($header);//组合签名
        $response=$this->post($url,$data,$header);  $result = $this->toJson($response); //$request测试
        return $result;
    }
    //获取token
    public function getAdminToken(){
        $url = $this->baseHost.'/code-login';
        $data=['mobile'=>'13000000000', 'code'=>'123456', 'type'=>'login'];
        $header=[ 'source'=>'h5', ];
        $response=$this->post($url,$data,$header);  $result = $this->toJson($response); //$request测试
        return $result;
    }
    //获取表单token
    public function getFormToken($token){
        $url = $this->baseHost.'/get-form-token';
        $data = [];
        $header = [ 'token'=> $token ];
        //var_dump('getFormToken');//
        $response=$this->get($url,$data,$header);  $result = $this->toJson($response); //$request测试
        return $result;
    }

    /*
     * 根据命令行关键字 - 清除 缓存id & 数据库痕迹
     */
    protected function tableCleanByArgv($name,$action=null){
        //自定义 命令行参数
        $actions = ['clean','stay'];
        $argv = $_SERVER['argv'];

        if( isset($argv[2]) && in_array($argv[2],$actions) ){
            switch ($argv[2]){
                default: break; //不清除 缓存ID和数据
                case 'clean': $this->tableCleanCache($name); break; //清除 所有 缓存ID和数据
                case 'stay': $this->tableCleanCache($name,true); break; //清除 缓存ID 但 保留数据
            }
        }
        //自定义 传参
        else if(!empty($action)){
            switch ($action){
                default: break; //不清除 缓存ID和数据
                case 'clean': $this->tableCleanCache($name); break; //清除 所有 缓存ID和数据
                case 'stay': $this->tableCleanCache($name,true); break; //清除 缓存ID 但 保留数据
            }
        }
    }

    /*
     * 清除 缓存id & 数据库痕迹
     * 说明: 不使用时, tablePushTempId()使 redis缓存数据id递增; 使用时,就连同历史数据清除
     * @param $stayData [ true ,清除缓存id 但 保留数据 ]
     */
    protected function tableCleanCache($name,$stayData=false):void{
        $haveIds = $this->tableGetTempIds($name);
        if($haveIds){
            //测试完 清除缓存入库数据
            $this->tableCleanTempIds($name);
            if( !$stayData ){ //是否保留数据
                //数据库清理逻辑
                $this->tableClean($name,$haveIds);
            }
        }
    }

    /*
     * 缓存入库数据id,待测试完清除
     */
    public function tablePushTempId($name,$id){ $result = null;
        //缓存入库数据id,待测试完清除
        $dataIds = []; $have = $this->getTempData($name); if ( !empty( $have ) ){ $dataIds = $have; };
        array_push( $dataIds , $id ); $result = $this->setTempData( $dataIds ,$name);
        return $result;
    }
    public function tableMergeTempIds($name,$ids){ $result = null;
        //缓存入库数据id,待测试完清除
        $dataIds = []; $have = $this->getTempData($name); if ( !empty( $have ) ){ $dataIds = $have; };
        $dataIds = array_keys(array_flip($dataIds)+array_flip($ids)); //去重合并
        $result = $this->setTempData( $dataIds ,$name);
        return $result;
    }
    public function tableGetTempIds($name){
        return $this->getTempData($name);
    }
    public function tableCleanTempIds($name){ $result = null;
        $haveIds = $this->tableGetTempIds($name);
        if ( !empty( $haveIds ) ){ $result =$this->removeTempData($name);}
        return $result;
    }

    /*
     * 数据库清理逻辑 - 包括回滚自增id
     */
    public function tableClean($name,$ids){

        $result = DB::table($name)->delete($ids);
        $num = $ids[0];
        $tableName = config('database.connections.mysql.prefix').$name;
        $sql = "alter table ".$tableName." auto_increment = ".$num;
        DB::select($sql);

        return $result;
    }

    public function setTempData($data,$mine= "temp_data"){
        $ApiCacheRedis =  new CacheRedis(9);
        $keyName = implode("_", (explode('\\',__CLASS__)) ).'_';
        $ApiCacheRedis->setDataByMineKey($keyName,json_encode($data),$mine);
    }
    public function getTempData($mine= "temp_data"){
        $ApiCacheRedis =  new CacheRedis(9);
        $keyName = implode("_", (explode('\\',__CLASS__)) ).'_';
        return json_decode( $ApiCacheRedis->getDataByMineKey($keyName,$mine) );
    }
    public function removeTempData($mine= "temp_data"){
        $ApiCacheRedis =  new CacheRedis(9);
        $keyName = implode("_", (explode('\\',__CLASS__)) ).'_';
        return json_decode( $ApiCacheRedis->delDataByMineKey($keyName,$mine) );
    }

    public function httpRequest($method,$url, $data = null, $header = null, &$res=null){

        $header = $this->makeSignData($header);//组合签名

        $res = $this->http($method,$url, $data, $header);
        $result = json_decode( $res, true );
        if(isset($result['data']['trace'])){  unset($result['data']['trace']); }
        return $result;
    }

    public function http( $method='post', $url, $data = null, $header = null){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($curl, CURLOPT_HEADER, 0);//设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);//设置获取的信息以文件流的形式返回，而不是直接输出。

        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_ENCODING, '');

        if(!empty($header)){
            $curlHeader=[];
            foreach ($header as $k => $v) {
                $curlHeader[] = $k . ": " . $v;
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeader);
        }

        // 判断要执行的 CURL 的请求方式
        $method=strtoupper( $method );
        switch ( $method ) {
            case 'GET':
                curl_setopt( $curl, CURLOPT_HTTPGET, true ); // 设置请求方式为 GET
                break;
            case 'POST':
                curl_setopt( $curl, CURLOPT_POST, true ); // 设置请求方式为 POST
                curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($data) );// 设置请求体，提交数据包
                break;
            case 'PUT':
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'PUT' );// 设置请求方式为 PUT
                curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($data) );//设置请求体，提交数据包
                break;
            case 'DELETE':
                curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'DELETE' );// 设置请求方式为 DELETE
                break;
            default:
                echo "不存在请求方式";
                die();
        }

        $output = curl_exec($curl);
        $curlInfo=curl_getinfo($curl);

        curl_close($curl);
        return $output;
    }


    //抽象类继承 必须实现方法
    public function count(): int{
        return parent::count();
    }
    public function toString(): string{
        return parent::toString();
    }
    public function run(TestResult $result = null): TestResult{
        return parent::run($result);
    }


    //补充部分

    public function toJson($response){
        $arr= json_decode($response->content(),true);

        echo  "\033[0mSTATUS:: ";
        echo  "\033[33m";
        var_dump($response->getStatusCode());//

        echo  "\033[0mDATA:: ";
        echo  "\033[33m";
        var_dump($arr);//

        return $arr;
    }




}