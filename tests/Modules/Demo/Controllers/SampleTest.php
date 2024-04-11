<?php
namespace Test\Modules\Demo\Controllers;

use Extend\ArtisanTest\Base\Api\ApiToDoc;
use Extend\ArtisanTest\Base\Api\BaseApiTest;
use Illuminate\Support\Facades\DB;
use Extend\ArtisanTest\Base\Api\TableToDoc;

class SampleTest extends BaseApiTest{

    protected $mainTable = 'samples';

    protected $baseHost;
    protected $stack;

    protected $userToken;
    protected $adminToken;
    protected $formToken;

    //基境初始化
    public static function setUpBeforeClass():void{
    }
    protected function setUp():void{
        $this->stack = [];
        $this->baseHost = '';

        //$this->userToken = $this->getUserToken()['data']['token'];
        //$this->adminToken = $this->getAdminToken()['data']['token'];
        //$this->formToken = $this->getFormToken( $this->adminToken )->data->__token__;
    }
    public static function tearDownAfterClass():void{
    }
    protected function tearDown():void{
        $this->stack = [];
        $this->baseHost = null;

        $this->userToken = null;
        $this->adminToken = null;
        $this->formToken = null;
    }
    //#

    //测试用例
    /**
     * 生产者
     */
    //{@hidden
    public function testSampleUrlCreate(){
        $uri = '/demo/sample/add';
        $url = $this->baseHost.$uri;

        //$formToken = $this->formToken;
        $data = [
            //@in_data
            "name" => "create新增",
            "nick_name" => "",
            "mobile" => "18588889999",
            "photo" => "",
            "sex" => 0,
            "type" => 0,
            "status" => 0,
            //@in_data
        ];
        $header=[
            'token'=> $this->userToken
        ];
        $response = $this->post($url,$data,$header);  $result = $this->toJson($response); //$request测试

        //生成文档
        $doc = new ApiToDoc("demo","sample","create",$this->test_path);
        $doc->setMethod('post')->setUri($uri)->setData($data,$header)->setResponse($result)->write();

        if( !empty($result) ){
            $result = DB::table( $this->mainTable )->where( 'created_at','<>',null )->orderBy('id','desc')->first();
            $result = (array)$result;
            $this->assertArrayHasKey('id',$result );
            //缓存入库数据id,待测试完清除
            $this->tablePushTempId( $this->mainTable, $result['id'] );
            return $result;
        }

        return null;
    }
    //@hidden}

    /**
     * 消费者 依赖 depends
     * @depends testSampleUrlCreate
     * @param $data
     * @return mixed
     */
    //{@hidden
    public function testSampleUrlUpdate($data){

        $uri = '/demo/sample'.'/'.$data['id']; $query = '';
        $url = $this->baseHost.$uri.$query;
        //$formToken = $this->formToken;
        $data = [
            //@up_data
            "name" => "update更新",
            "nick_name" => "",
            "mobile" => "18588889999",
            "photo" => "",
            "sex" => 0,
            "type" => 0,
            "status" => 0,
            //@up_data
        ];
        $header=[
            'token'=> $this->userToken
        ];
        $response=$this->put($url,$data,$header); $result = $this->toJson($response); //$request测试

        //生成文档
        $doc = new ApiToDoc("demo","sample","update",$this->test_path);
        $doc->setMethod('put')->setUri($uri,$query)->setData($data,$header)->setResponse($result)->write();

        $this->assertTrue( !empty($result) );
    }
    //@hidden}

    /**
     * 消费者 依赖 depends
     * @depends testSampleUrlCreate
     * @param $data
     * @return mixed
     */
    //{@hidden
    public function testSampleUrlDetail($data){

        $uri = '/demo/sample'.'/'.$data['id'];  $query ='?_time=1';
        $url = $this->baseHost.$uri.$query;

        $data = [];
        $header=[
            'token'=> $this->userToken
        ];
        $response=$this->get($url,$header); $result = $this->toJson($response); //$request测试

        //生成文档
        $doc = new ApiToDoc("demo","sample","detail",$this->test_path);
        $doc->setMethod('get')->setUri($uri,$query)->setData($data,$header)->setResponse($result)->write();

        $this->assertTrue( !empty($result) );

        return $result;
    }
    //@hidden}

    /**
     * 生产者
     */
    //{@hidden
    public function testSampleUrlCollect(){

        $uri = '/demo/sample/list'; $query='?_time=1';
        $url = $this->baseHost.$uri;

        $data = [];
        $header=[
            'token'=> $this->userToken
        ];
        $response=$this->get($url,$data,$header); $result = $this->toJson($response); //$request测试

        //生成文档
        $doc = new ApiToDoc("demo","sample","collect",$this->test_path);
        $doc->setMethod('get')->setUri($uri,$query)->setData($data,$header)->setResponse($result)->write();

        $this->assertTrue( !empty($result) );

        return $result;
    }
    //@hidden}


    /**
     * 消费者 依赖 depends
     * @depends testSampleUrlCreate
     * @param $data
     * @return mixed
     */
    //{@hidden
    public function testSampleUrlDelete($data){

        $uri = '/demo/sample'.'/'.$data['id']; $query = '';
        $url = $this->baseHost.$uri.$query;

        $data = [];
        $header=[
            'token'=> $this->userToken
        ];
        $response=$this->delete($url,$data,$header); $result = $this->toJson($response); //$request测试

        //生成文档
        $doc = new ApiToDoc("demo","sample","delete",$this->test_path);
        $doc->setMethod('delete')->setUri($uri,$query)->setData($data,$header)->setResponse($result)->write();

        $this->assertTrue( $result==null );
        return $result;
    }
    //@hidden}

    /**
     * 结束测试
     */
    public function testEnd(){
        $this->tableCleanByArgv($this->mainTable,'clean');

        //生成文档
        ( new TableToDoc("demo","sample","table",$this->test_path) )->write();

        $this->assertTrue(true);
    }


}