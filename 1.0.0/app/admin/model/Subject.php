<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;
use think\Db;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class Subject extends Model
{
    public static function getSubject($school_type = 'vocational')
    {
        $subject = Db::name('subjects')->where(array('school_type' => $school_type))->find();
        $subject['subjects'] = json_decode($subject['subjects']);
        return $subject;
    }
}
