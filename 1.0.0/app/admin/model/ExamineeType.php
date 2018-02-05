<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use app\admin\model\MemberList;
use think\Model;
use think\Db;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class ExamineeType extends Model
{
    public static function getExamineeTypes()
    {
        $data = Db::name('examinee_type')->order('examinee_type_id asc')->select();
        return $data;
    }
    public static function getExamineeType($id)
    {
        $data = Db::name('examinee_type')->where(['examinee_type_id' => $id])->find();
        return $data;
    }
    public static function getPExamineeTypes()
    {
        $examinee_types = Db::name('examinee_type')->where(['exparentid' => 0])->select();
        return $examinee_types;
    }
    public static function getExamineeType1()
    {
        $data = Db::name('examinee_type')->where(['examinee_type_id' => $id])->find();
        return $data;
    }
}
