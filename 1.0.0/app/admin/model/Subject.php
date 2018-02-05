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
    public static function getSubject($where)
    {
        $subject = Db::name('subjects')->where($where)->find();
        return $subject;
    }
    public static function getSubjects($where)
    {
        $subject = Db::name('subjects')->where($where)->select();
        return $subject;
    }
    //获取父类数据，例如返回 面试，学业水平，笔试，专业技能考核
    public static function getPSubjects($where = [])
    {
        $subjects = Db::name('subjects')->alias('sub')->join('zs_examinee_type et','sub.examinee_type_id = et.examinee_type_id')->where(array('subparentid' => 0))->where($where)->order('sub.examinee_type_id asc')->order('sub.subject_id asc')->select();
        return $subjects;
    }
    //获取子类数据（有则删除父类） 例如返回 面试，学业水平，综合文化理论，专业技能理论，专业技能考核
    public static function getCSubjects($where = [])
    {
        $subjects = Db::name('subjects')->alias('sub')->join('zs_examinee_type et','sub.examinee_type_id = et.examinee_type_id')->where($where)->order('sub.examinee_type_id asc')->order('sub.subject_id asc')->select();
        foreach($subjects as $key => $subject)
        {
            $csubject = Db::name('subjects')->where(['subparentid' => $subject['subject_id']])->find();
            if($csubject)
            {
                unset($subjects[$key]);
            }
        }
        return $subjects;
    }
    //根据 subject_id 判断有无子类，有则返回，无则返回本身，例如返回 面试  或  综合文化理论，专业技能理论
    public static function getPCSubjects($subject_id)
    {
        $subject = Db::name('subjects')->where(['subject_id' => $subject_id])->find();
        $csubjects = Db::name('subjects')->where(['subparentid' => $subject_id])->select();
        $data = array();
        if($csubjects)
        {
            $data = $csubjects;
        }
        else{
            $data[] = $subject;
        }
        return $data;
    }
    // public static function getIPSubjects($where)
    // {
    //     $subjects = Db::name('subjects')->where($where)->where()
    // }
}
