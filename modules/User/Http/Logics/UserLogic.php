<?php

namespace Modules\User\Http\Logics;

use Extend\Util\QueryMatch;
use Illuminate\Support\Facades\DB;
use Modules\Base\Exception\Exception;
use Modules\Base\Service\BaseService;
use Modules\Oauth\Repositories\OauthClientRepo;
use Modules\Oauth\Repositories\OauthRoleRepo;
use Modules\Oauth\Services\OauthTokenService;
use Modules\User\Exceptions\UserRootError;
use Modules\User\Repositories\UserRepo;

/**
 * notes: 应用层-业务类
 * 说明: 业务类数据操作,一般不直接调用模型,通过仓储类提供存粹的数据执行函数, 跨 应用端/模块 操作同一数据类型的业务, 建议抽象到 领域层-业务类, 减少冗余.
 * 调用原则: 向下调用[仓储类,领域层-业务类]
 */
class UserLogic extends BaseService
{

    /*
     * 用户-新增-注册
     */
    public function userRegister(&$requestInput)
    {
        $UserRepo = UserRepo::searchInstance();
        $UserRepo->isMobileUnique($requestInput['mobile']);

        $requestInput['pass_word'] = md5($requestInput['pass_word']);

        //业务逻辑
        $builder = UserRepo::newInstance($requestInput);
        $builder->saveOrFail();
        $result = $builder->fresh();

        return $result;
    }


    /*
     * 用户-更新-登录
     */
    public function userLogin($mobile, &$requestInput)
    {

        //检查权限
        $scopeId         = $requestInput['scope_id'];
        $clientId        = $requestInput['client_id'];
        $OauthClientRepo = OauthClientRepo::searchInstance();
        $oauthClient     = $OauthClientRepo->isOauthClientExit($scopeId, $clientId);
        $clientSecret    = $oauthClient->client_secret;

        //业务逻辑
        $UserRepo = UserRepo::searchInstance();
        $builder  = $UserRepo->isMobileExit($mobile);
        $userId   = $builder->id;
        $userRole = $builder->role;

        //验证密码
        $pw = md5($requestInput['pass_word']);
        unset($requestInput['pass_word']);
        if ($builder->pass_word != $pw) {
            Exception::app(UserRootError::code("PASS_WORD_WRONG"), UserRootError::msg("PASS_WORD_WRONG"), __METHOD__);
        }

        $now                          = date("Y-m-d H:i:s", time());
        $requestInput['on_line_time'] = $now;

        //自动事务函数
        $result = DB::transaction(function () use (&$builder, $userId, $userRole, $scopeId, $clientId, $clientSecret, $requestInput) {

            //更新用户登录
            $builder->fill($requestInput);
            $builder->saveOrFail();
            $result = $builder->fresh();

            $extData = [
                'mobile' => $requestInput['mobile'],
            ];

            //生成 access_token ;
            if ($result) {

                $oauthInput = [
                    'scope_id' => $scopeId, 'client_id' => $clientId, 'client_secret' => $clientSecret,
                    'expire'   => 7200,
                ];
                //记录token
                $OauthTokenService = new OauthTokenService();
                $oauthToken        = $OauthTokenService->oauthTokenCreateByUser($userId, $userRole, $oauthInput, $extData);
                $result->id        = $userId;
                $result->role      = $userRole;
                $result->auth      = $oauthToken;

                return $result;
            }

            return false;
        });

        return $result;
    }

    /*
     * 用户-更新-自己的详情
     */
    public function userMeUpdate($userId, &$requestInput)
    {
        //业务逻辑
        $UserRepo = UserRepo::searchInstance();
        $builder  = $UserRepo->isExit($userId);

        //检查角色
        if (isset($requestInput['role'])) {
            $OauthRoleRepo = OauthRoleRepo::searchInstance();
            $OauthRoleRepo->isRoleIdExit($requestInput['role']);
        }

        //更新用户登录
        $builder->fill($requestInput);
        $builder->saveOrFail();
        $result = $builder->fresh();

        return $result;
    }


    /*
     * 用户-获取-自己的详情
     */
    public function userMeDetail(array $requestQuery, $userId)
    {
        //业务逻辑
        //{@field_detail
        $fields = ["id", "role", "nick_name", "avatar", "sex", "mobile", "pass_word", "client_driver", "client_type",
            "lat", "lng", "status", "on_line_time", "off_line_time", "created_at", "updated_at", "deleted_at"];
        //@field_detail}

        //主表筛选逻辑-获取query查询表达式参数
        $QM = QueryMatch::instance($requestQuery);

        //?key=value 范围查询
        $builder = UserRepo::searchInstance($fields);
        $builder->queryMatchDetail($QM);

        //默认排序
        $builder->orderBy('updated_at', 'desc');

        if (!empty($id)) {
            $result = $builder->find($userId);
        } else {
            $result = $builder->first();
        }

        //dd($result->toArray());//

        return $result;
    }


    /*
     * 管理员-获取-用户列表
     */
    public function userAdmCollect(array $requestQuery)
    {
        //业务逻辑
        //{@field_collect
        $fields = ["id", "role", "nick_name", "avatar", "sex", "mobile", "pass_word", "client_driver", "client_type",
            "lat", "lng", "status", "on_line_time", "off_line_time", "created_at", "updated_at", "deleted_at"];
        //@field_collect}

        //主表筛选逻辑-获取query查询表达式参数
        $QM = QueryMatch::instance($requestQuery);

        $builder = UserRepo::searchInstance($fields);
        //?key=value 范围查询
        $builder->queryMatchCollect($QM);

        //?_extend=param 副表扩展查询-用于附加查询条件,不是数据输出.
        $builder->scopeExtend($requestQuery);

        //默认排序
        $builder->orderBy('updated_at', 'desc');

        //?_pagination=true 翻页查询
        $result = $builder->pageGet($QM);
        //dd($result['data']->toArray());//

        return $result;
    }


    /*
     * 管理员-获取-用户详情
     */
    public function userAdmDetail(array $requestQuery, $userId)
    {
        //业务逻辑
        //{@field_detail
        $fields = ["id", "role", "nick_name", "avatar", "sex", "mobile", "pass_word", "client_driver", "client_type",
            "lat", "lng", "status", "on_line_time", "off_line_time", "created_at", "updated_at", "deleted_at"];
        //@field_detail}

        //主表筛选逻辑-获取query查询表达式参数
        $QM = QueryMatch::instance($requestQuery);

        //?key=value 范围查询
        $builder = UserRepo::searchInstance($fields);
        $builder->queryMatchDetail($QM);

        //默认排序
        $builder->orderBy('updated_at', 'desc');

        if (!empty($id)) {
            $result = $builder->find($userId);
        } else {
            $result = $builder->first();
        }

        //dd($result->toArray());//

        return $result;
    }


    /*
     * 系统-删除-用户详情
     */
    public function userSysDelete($id)
    {
        //业务逻辑

        //软删除数据
        $builder = UserRepo::searchInstance();
        $result  = $builder->isExit($id);

        //软删除数据
        $result->delete();
        //恢复软删除数据
        //$builder->withTrashed()->where('id',$id)->restore();

        return $result;
    }

}