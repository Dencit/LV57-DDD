<?php

namespace Modules\Oauth\Database\Seeders;

use Modules\Base\Database\BaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OauthClientTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //DB::table("oauth_clients")->truncate();//清空

        $data = [
            ['client' => 'H5端', 'client_id' => 'h5_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'user_auth', 'created_at' => date('Y-m-d H:i:s') ],
            ['client' => '微信端', 'client_id' => 'wechat_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'user_auth', 'created_at' => date('Y-m-d H:i:s') ],
            ['client' => '后台管理端', 'client_id' => 'admin_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'admin_auth', 'created_at' => date('Y-m-d H:i:s') ],
            ['client' => '后台系统管理端', 'client_id' => 'system_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'system_auth', 'created_at' => date('Y-m-d H:i:s') ],
            ['client' => '安卓端', 'client_id' => 'android_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'user_auth', 'created_at' => date('Y-m-d H:i:s') ],
            ['client' => 'IOS端', 'client_id' => 'ios_client', 'client_secret' => $this->createSecret(64),
                'scope_id' => 'user_auth', 'created_at' => date('Y-m-d H:i:s') ],
        ];


        $ids = array_column($data,'client_id');
        //获取旧数据 - 去重
        $rows = $this->oldDataExit('oauth_clients','client_id',$ids);
        foreach ($data as $ind=>$column ){
            $value = $column['client_id'];
            if( in_array($value,$rows) ){ unset($data[$ind]); }
        }

        if(!empty($data)){
            $data = array_values($data);
            DB::table('oauth_clients')->insert($data);
        }

    }


}
