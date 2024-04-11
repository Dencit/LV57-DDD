<?php
/**
 * notes: 模块API路由 - 必须
 */

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

//开放权限
Route::prefix('user')->group(function () {

    //只对测试开放-正式接口不要放这里
    if (config('app.debug')) {
        //-批量新增
        //Route::post('/batch-in',AdminController::class.'@adminBatchCreate');
        //-批量更新
        //Route::put('/batch-up', AdminController::class.'@adminBatchUpdate');
    }

    //用户-新增-注册
    Route::post('/register', UserController::class . '@userRegister');
    //用户-更新-登录
    Route::put('/login', UserController::class . '@userLogin');

});

//用户以上权限
Route::middleware('auth:user_auth,admin_auth,system_auth')->prefix('user')->group(function () {

    //用户-获取-自己的详情
    Route::get('/me', UserController::class . '@userMeDetail');
    //用户-更新-自己的详情
    Route::put('/me', UserController::class . '@userMeUpdate');

});

//管理以上权限
Route::middleware('auth:admin_auth,system_auth')->prefix('user')->group(function () {

    //管理员-获取-用户列表
    Route::get('/adm-list', UserController::class . '@userAdmCollect');
    //管理员-获取-用户详情
    Route::get('/adm/{id}', UserController::class . '@userAdmDetail')->where(['id' => '\d+']);
    //管理员-更新-用户详情
    Route::put('/adm/{id}', UserController::class . '@userAdmUpdate')->where(['id' => '\d+']);

});

//系统以上权限
Route::middleware('auth:system_auth')->prefix('user')->group(function () {

    //系统-删除-用户详情
    Route::delete('/sys/{id}', UserController::class . '@userSysDelete')->where(['id' => '\d+']);

});
