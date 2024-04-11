<?php

namespace Modules\Admin\Http\Requests;

use Modules\Base\Request\BaseRequest;

/**
 * notes: 应用层-输入验证类
 * desc: 只在此类 统一校验输入数据.
 * 内置规则: https://laravelacademy.org/post/9547
 */
class AdminRequest extends BaseRequest
{
    //验证规则
    protected $rules = [
        //@rules
        "id"            => "integer|gt:0|between:0,20",
        "user_id"       => "integer|gt:0|between:0,20",
        "role"          => "string|between:0,255",
        "name"          => "string|between:0,255",
        "avatar"        => "url|between:0,255",
        "sex"           => "integer|in:0,1,2",
        "mobile"        => "string|between:0,30",
        "pass_word"     => "string|between:0,255",
        "client_driver" => "string",
        "client_type"   => "integer|gt:0|between:0,3",
        "lat"           => "numeric|gt:0|between:0,10",
        "lng"           => "numeric|gt:0|between:0,10",
        "status"        => "integer|in:1,2",
        "on_line_time"  => "date",
        "off_line_time" => "date",
        "created_at"    => "date",
        "updated_at"    => "date",
        "deleted_at"    => "date",
        //@rules
    ];

    //
    protected $messages = [
        //@messages
        'id.integer' => '主键 id 必须是整数',
        'id.gt'      => '主键 id 必须大于0',
        'id.gte'     => '主键 id 必须大于等于0',
        'id.max'     => '主键 id 超出最大值',
        'id.min'     => '主键 id 超出最小值',
        'id.in'      => '主键 id 数值超出许可范围',
        'id.between' => '主键 id 最大长度是 20',

        'user_id.integer' => '关联用户id 必须是整数',
        'user_id.gt'      => '关联用户id 必须大于0',
        'user_id.gte'     => '关联用户id 必须大于等于0',
        'user_id.max'     => '关联用户id 超出最大值',
        'user_id.min'     => '关联用户id 超出最小值',
        'user_id.in'      => '关联用户id 数值超出许可范围',
        'user_id.between' => '关联用户id 最大长度是 20',

        'role.string'     => '管理员角色 包含非法字符-只能是字符串',
        'role.alpha'      => '管理员角色 包含非法字符-只能是/字母',
        'role.alpha_num'  => '管理员角色 包含非法字符-只能是/字母/数字',
        'role.alpha_dash' => '管理员角色 包含非法字符',
        'role.between'    => '管理员角色 最大长度是 255',

        'name.string'     => '管理员名称 包含非法字符-只能是字符串',
        'name.alpha'      => '管理员名称 包含非法字符-只能是/字母',
        'name.alpha_num'  => '管理员名称 包含非法字符-只能是/字母/数字',
        'name.alpha_dash' => '管理员名称 包含非法字符',
        'name.between'    => '管理员名称 最大长度是 255',

        'avatar.string'     => '管理员头像 包含非法字符-只能是字符串',
        'avatar.alpha'      => '管理员头像 包含非法字符-只能是/字母',
        'avatar.alpha_num'  => '管理员头像 包含非法字符-只能是/字母/数字',
        'avatar.alpha_dash' => '管理员头像 包含非法字符',
        'avatar.between'    => '管理员头像 最大长度是 255',
        'avatar.url'        => '用户头像 必须是http链接',

        'sex.integer' => '性别 必须是整数',
        'sex.gt'      => '性别 必须大于0',
        'sex.gte'     => '性别 必须大于等于0',
        'sex.max'     => '性别 超出最大值',
        'sex.min'     => '性别 超出最小值',
        'sex.in'      => '性别 数值超出许可范围',
        'sex.between' => '性别 最大长度是 3',

        'mobile.string'     => '绑定手机 包含非法字符-只能是字符串',
        'mobile.alpha'      => '绑定手机 包含非法字符-只能是/字母',
        'mobile.alpha_num'  => '绑定手机 包含非法字符-只能是/字母/数字',
        'mobile.alpha_dash' => '绑定手机 包含非法字符',
        'mobile.between'    => '绑定手机 最大长度是 30',

        'pass_word.string'     => '密码 包含非法字符-只能是字符串',
        'pass_word.alpha'      => '密码 包含非法字符-只能是/字母',
        'pass_word.alpha_num'  => '密码 包含非法字符-只能是/字母/数字',
        'pass_word.alpha_dash' => '密码 包含非法字符',
        'pass_word.between'    => '密码 最大长度是 255',

        'client_driver.string'     => '客户端信息 包含非法字符-只能是字符串',
        'client_driver.alpha'      => '客户端信息 包含非法字符-只能是/字母',
        'client_driver.alpha_num'  => '客户端信息 包含非法字符-只能是/字母/数字',
        'client_driver.alpha_dash' => '客户端信息 包含非法字符',
        'client_driver.between'    => '客户端信息 超出最大长度 是65536',

        'client_type.integer' => '客户端类型 必须是整数',
        'client_type.gt'      => '客户端类型 必须大于0',
        'client_type.gte'     => '客户端类型 必须大于等于0',
        'client_type.max'     => '客户端类型 超出最大值',
        'client_type.min'     => '客户端类型 超出最小值',
        'client_type.in'      => '客户端类型 数值超出许可范围',
        'client_type.between' => '客户端类型 最大长度是 3',

        'lat.numeric' => '坐标 必须是数字或小数',
        'lat.gt'      => '坐标 必须大于0',
        'lat.gte'     => '坐标 必须大于等于0',
        'lat.max'     => '坐标 超出最大值',
        'lat.min'     => '坐标 低于最小值',
        'lat.in'      => '坐标 数值超出许可范围',
        'lat.between' => '坐标 最大长度是 10',

        'lng.numeric' => '坐标 必须是数字或小数',
        'lng.gt'      => '坐标 必须大于0',
        'lng.gte'     => '坐标 必须大于等于0',
        'lng.max'     => '坐标 超出最大值',
        'lng.min'     => '坐标 低于最小值',
        'lng.in'      => '坐标 数值超出许可范围',
        'lng.between' => '坐标 最大长度是 10',

        'status.integer' => '状态 必须是整数',
        'status.gt'      => '状态 必须大于0',
        'status.gte'     => '状态 必须大于等于0',
        'status.max'     => '状态 超出最大值',
        'status.min'     => '状态 超出最小值',
        'status.in'      => '状态 数值超出许可范围',
        'status.between' => '状态 最大长度是 3',

        'on_line_time.date'        => '登录时间 日期时间格式有误',
        'on_line_time.date_format' => '登录时间 自定义日期格式有误',
        'on_line_time.required'    => '登录时间 不能为空',

        'off_line_time.date'        => '登出时间 日期时间格式有误',
        'off_line_time.date_format' => '登出时间 自定义日期格式有误',
        'off_line_time.required'    => '登出时间 不能为空',

        'created_at.date'        => '创建时间|注册时间 日期时间格式有误',
        'created_at.date_format' => '创建时间|注册时间 自定义日期格式有误',

        'updated_at.date'        => '更新时间 日期时间格式有误',
        'updated_at.date_format' => '更新时间 自定义日期格式有误',
        'updated_at.required'    => '更新时间 不能为空',

        'deleted_at.date'        => '删除时间 日期时间格式有误',
        'deleted_at.date_format' => '删除时间 自定义日期格式有误',
        'deleted_at.required'    => '删除时间 不能为空',

        //@messages
    ];

    //edit 验证场景 定义方法
    //例子: $this->only(['name','age']) ->append('name', 'min:5') ->remove('age', 'between') ->append('age', 'require|max:100');
    public function sceneCreate()
    {
        return $this
            ->append('name', 'required')
            ->append('mobile', 'required')
            ->append('pass_word', 'required');
    }

    public function sceneUpdate()
    {
        //return $this->append('id', 'required');
    }

    public function sceneLogin()
    {
        return $this
            ->append('mobile', 'required')
            ->append('pass_word', 'required');
    }

    public function sceneDetail()
    {
        //return $this->append('id', 'required');
    }

    public function sceneDelete()
    {
        //return $this->append('id', 'required');
    }

}