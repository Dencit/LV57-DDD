<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->engine = 'innodb';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->bigIncrements('id')->comment('主键 id');

            $table->string('scope_id',255)->default('')->comment('授权范围-标记: 字符串');
            $table->string('client',255)->default('')->comment('授权客户端-描述: 字符串');
            $table->string('client_id',255)->default('')->comment('授权客户端-标记: 字符串');
            $table->string('client_secret',255)->default('')->comment('授权客户端-密匙: 字符串');

            $table->index('id');
            $table->index('scope_id');
            $table->index('client_id');
            $table->index(['scope_id','client_id']);

            $table->dateTime('created_at')->comment('创建时间');
            $table->dateTime('updated_at')->nullable()->comment('更新时间');
            $table->dateTime('deleted_at')->nullable()->comment('删除时间');
        });


        //补充表注释
        DB::statement(" ALTER TABLE oauth_clients COMMENT '授权客户端表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
