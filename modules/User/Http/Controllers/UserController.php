<?php

namespace Modules\User\Http\Controllers;

use Extend\Util\ApiCache;
use Illuminate\Http\Request;
use Modules\Base\Controller\BaseController;
use Modules\Base\Response\ApiTrans;
use Modules\User\Http\Logics\UserLogic;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Http\Transformers\UserTrans;

/**
 * notes: 应用层-控制器
 * 说明: 控制器内不写业务,只写http层面相关的逻辑,
 * 调用原则: 向下调用[输入验证类,业务类,输出转化类].
 */
class UserController extends BaseController
{

    /*
     * 用户-新增-注册
     */
    public function userRegister(Request $request)
    {
        //输入逻辑控制
        $requestInput = $request->post();
        $validate     = new UserRequest();
        $validate->checkSceneValidate('register', $requestInput);

        //业务逻辑控制
        $result = (new UserLogic())->userRegister($requestInput);

        //输出逻辑控制
        $result = ApiTrans::save($result);

        return ApiTrans::response($result);
    }

    /*
     * 用户-更新-登录
     */
    public function userLogin(Request $request)
    {
        //输入逻辑控制
        $rules        = ['id'];
        $requestInput = $request->except($rules);
        $validate     = new UserRequest();
        $validate->checkSceneValidate('login', $requestInput);

        //业务逻辑控制
        $result = (new UserLogic())->userLogin($requestInput['mobile'], $requestInput);

        //输出逻辑控制
        $result = ApiTrans::update($result);

        return ApiTrans::response($result);
    }

    /*
     * 用户-更新-自己的详情
     */
    public function userMeUpdate(Request $request)
    {
        $userId = $this->auth('user_id');

        //输入逻辑控制
        $rules        = ['nick_name', 'avatar', 'sex'];
        $requestInput = $request->only($rules);
        $validate     = new UserRequest();
        $validate->checkValidate($requestInput);

        //业务逻辑控制
        $result = (new UserLogic())->userMeUpdate($userId, $requestInput);

        //输出逻辑控制
        $result = ApiTrans::update($result);

        return ApiTrans::response($result);
    }

    /*
     * 用户-获取-自己的详情
     */
    public function userMeDetail(Request $request)
    {
        $userId = $this->auth('user_id');

        //query string
        $requestQuery = $request->query();

        //api查询缓存
        $hKey     = ApiCache::makeHKeyByClassMethod(__CLASS__ . '@' . __FUNCTION__);
        $queryKey = ApiCache::makeQueryKeyByRequest($requestQuery);
        $queryKey .= '&user_id=' . $userId;
        $result   = (new ApiCache)->collect(
            $hKey, $queryKey, function () use ($requestQuery, $userId) {

            //业务逻辑控制
            $logic  = new UserLogic();
            $result = $logic->userMeDetail($requestQuery, $userId);

            //输出逻辑控制
            return ApiTrans::read($result, UserTrans::class, 'transform');

        }, -1
        );

        return ApiTrans::response($result);
    }

    /*
     * 管理员-获取-用户列表
     */
    public function userAdmCollect(Request $request)
    {
        //$adminId = $this->auth('admin_id');

        //query string
        $requestQuery = $request->query();

        //api查询缓存
        $hKey     = ApiCache::makeHKeyByClassMethod(__CLASS__ . '@' . __FUNCTION__);
        $queryKey = ApiCache::makeQueryKeyByRequest($requestQuery);
        //$queryKey .= '&admin_id=' . $adminId;
        $result = (new ApiCache)->collect(
            $hKey, $queryKey, function () use ($requestQuery) {

            //业务逻辑控制
            $result = (new UserLogic())->userAdmCollect($requestQuery);

            //输出逻辑控制
            return ApiTrans::index($result, UserTrans::class, 'transform');

        }, -1
        );

        return ApiTrans::response($result);

    }

    /*
     * 管理员-获取-用户详情
     */
    public function userAdmDetail(Request $request, $userId)
    {
        //$adminId = $this->auth('admin_id');

        //query string
        $requestQuery = $request->query();

        //api查询缓存
        $hKey     = ApiCache::makeHKeyByClassMethod(__CLASS__ . '@' . __FUNCTION__);
        $queryKey = ApiCache::makeQueryKeyByRequest($requestQuery);
        //$queryKey .= '&admin_id=' . $adminId;
        $result = (new ApiCache)->collect(
            $hKey, $queryKey, function () use ($requestQuery, $userId) {

            //业务逻辑控制
            $logic  = new UserLogic();
            $result = $logic->userAdmDetail($requestQuery, $userId);

            //输出逻辑控制
            return ApiTrans::read($result, UserTrans::class, 'transform');

        }, -1
        );

        return ApiTrans::response($result);
    }

    /*
     * 管理员-更新-用户详情
     */
    public function userAdmUpdate(Request $request, $userId)
    {
        //$adminId = $this->auth('admin_id');

        //输入逻辑控制
        $rules        = ['nick_name', 'avatar', 'sex'];
        $requestInput = $request->only($rules);
        $validate     = new UserRequest();
        $validate->checkValidate($requestInput);

        //业务逻辑控制
        $result = (new UserLogic())->userMeUpdate($userId, $requestInput);

        //输出逻辑控制
        $result = ApiTrans::update($result);

        return ApiTrans::response($result);
    }

    /*
     * 系统-删除-用户详情
     */
    public function userSysDelete($id)
    {

        //业务逻辑控制
        $logic  = new UserLogic();
        $result = $logic->userSysDelete($id);

        //输出逻辑控制
        $result = ApiTrans::delete($result);

        return ApiTrans::response($result);
    }

}
