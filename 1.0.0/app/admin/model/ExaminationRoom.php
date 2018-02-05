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

class ExaminationRoom extends Model
{
    public static function getRoomSubjects()
    {
        return Db::name('examination_room')->alias('e')->join(config('database.prefix').'subjects sub','e.subject_id = sub.subject_id')->field('e.*,sub.subject_name')->group('subject_id')->select();
    }
    public static function getAllRooms()
    {
        return Db::name('examination_room')->alias('e')->join(config('database.prefix').'subjects sub','e.subject_id = sub.subject_id')->field('e.*,sub.subject_name')->group('room_id')->select();
    }
}
