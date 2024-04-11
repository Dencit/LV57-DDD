<?php
/**
 * notes: 模块API路由 - 必须
 */

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;

//开放权限
Route::prefix('admin')->group(function () {

    //只对测试开放-正式接口不要放这里
    if (config('app.debug')) {
        //-批量新增
        //Route::post('/batch-in',AdminController::class.'@adminBatchCreate');
        //-批量更新
        //Route::put('/batch-up', AdminController::class.'@adminBatchUpdate');
    }

    //管理-更新-登录
    Route::put('/login', AdminController::class . '@adminLogin');

});

//用户以上权限
Route::middleware('auth:user_auth,admin_auth,system_auth')->prefix('admin')->group(function () {

});

//管理以上权限
Route::middleware('auth:admin_auth,system_auth')->prefix('admin')->group(function () {

    //管理-获取-自己的信息
    Route::get('/me', AdminController::class . '@adminMeDetail');
    //管理员-更新-自己的详情
    Route::put('/me', AdminController::class . '@adminMeUpdate');

});

//系统以上权限
Route::middleware('auth:system_auth')->prefix('admin')->group(function () {

    //系统-新增-管理员
    Route::post('/sys', AdminController::class . '@adminSysCreate');
    //系统-获取-管理员列表
    Route::get('/sys-list', AdminController::class . '@adminSysCollect');
    //系统-获取-管理员详情
    Route::get('/sys/{id}', AdminController::class . '@adminSysDetail')->where(['id' => '\d+']);
    //系统-更新-管理员详情
    Route::put('/sys/{id}', AdminController::class . '@adminSysUpdate');
    //系统-删除-管理员信息
    Route::delete('/sys/{id}', AdminController::class . '@adminSysDelete')->where(['id' => '\d+']);

});