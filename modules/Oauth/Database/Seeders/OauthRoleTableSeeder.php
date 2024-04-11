<?php

namespace Modules\Oauth\Database\Seeders;

use Modules\Base\Database\BaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OauthRoleTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        //DB::table("oauth_roles")->truncate();//清空

        $data = [
            ['role' => '用户角色', 'role_id' => 'user', 'created_at' => date('Y-m-d H:i:s') ],
            ['role' => '普通管理员角色', 'role_id' => 'admin', 'created_at' => date('Y-m-d H:i:s') ],
            ['role' => '运营管理员角色', 'role_id' => 'operate', 'created_at' => date('Y-m-d H:i:s') ],
            ['role' => '会计管理员角色', 'role_id' => 'accountant', 'created_at' => date('Y-m-d H:i:s') ],
            ['role' => '系统管理员角色', 'role_id' => 'system', 'created_at' => date('Y-m-d H:i:s') ]
        ];

        $ids = array_column($data,'role_id');
        //获取旧数据 - 去重
        $rows = $this->oldDataExit('oauth_roles','role_id',$ids);
        foreach ($data as $ind=>$column ){
            $value = $column['role_id'];
            if( in_array($value,$rows) ){ unset($data[$ind]); }
        }

        if(!empty($data)){
            $data = array_values($data);
            DB::table('oauth_roles')->insert($data);
        }

    }
}
