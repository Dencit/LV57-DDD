<?php

namespace Modules\User\Exceptions;

use Modules\Base\Exception\BaseErr;

/**
 * notes: 根模块-总错误码
 * desc: 错误码区间,根据模块下的 doc.md 定义来设置. 注意 按数据单元做好注释, 每个单元错误码预留20位数间隔.
 */
class UserRootError extends BaseErr
{

    protected static $data = [
        "ID_NOT_FOUND"         => ['code' => 201000, 'msg' => '用户ID 不存在'],
        "ID_NOT_UNIQUE"        => ['code' => 201001, 'msg' => '用户ID 已存在'],
        "BATCH_IDS_NOT_FOUND"  => ['code' => 201002, 'msg' => '批量数据中 有ID不存在'],
        "BATCH_IDS_NOT_UNIQUE" => ['code' => 201003, 'msg' => '批量数据中 有ID已存在'],
        "MOBILE_NOT_FOUND"     => ['code' => 201004, 'msg' => '手机号 不存在'],
        "MOBILE_NOT_UNIQUE"    => ['code' => 201005, 'msg' => '手机号 已存在'],
        "PASS_WORD_WRONG"      => ['code' => 201006, 'msg' => '密码错误'],
    ];

    static function code($type)
    {
        return self::$data[$type]['code'];
    }

    static function msg($type)
    {
        return self::$data[$type]['msg'];
    }
}
