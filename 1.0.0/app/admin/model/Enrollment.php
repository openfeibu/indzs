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
use app\admin\model\ExamineeType as ExamineeTypeModel;

class Enrollment extends Model
{
    public static function get_enrollment_recruit_major($school_id)
    {
        $recruit_major_list = Db::name('enrollment')->alias('e')
                                ->join(config('database.prefix').'recruit_major rm','rm.recruit_major_id = e.recruit_major_id')
                                ->where(['e.school_id' => $school_id])
                                ->select();
        return $recruit_major_list;
    }
    public static function get_enrollment($enrollment_id)
    {
        $enrollment = Db::name('enrollment')->alias('e')
                            ->join(config('database.prefix').'recruit_major rm','rm.recruit_major_id = e.recruit_major_id')
                            ->where(['enrollment_id' => $enrollment_id])
                            ->find();
        $enrollment = self::handleEnrollment($enrollment);
        return $enrollment;
    }
    public static function handleEnrollment($enrollment)
    {
        $examinee_type_key = explode(',',$enrollment['examinee_type']);
        foreach ($examinee_type_key as $key => $value) {
            $examinee_type = ExamineeTypeModel::getExamineeType($value);
            $enrollment['examinee_type_val'][$value] = $examinee_type['name'];
        }
        return $enrollment;
    }
}
