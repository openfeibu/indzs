<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Subject as SubjectModel;
use app\admin\model\ExamineeType;
use think\Db;
use think\Cache;

class Subject extends Base
{
    public function subject_list()
    {
        $subjects = array();
        $subjects = SubjectModel::getCSubjects();
        $this->assign('subjects',$subjects);
        return $this->fetch();
    }
    public function price_setting()
    {
        $subjects = array();
        $subjects = SubjectModel::getPSubjects();
        $this->assign('subjects',$subjects);
        return $this->fetch();
    }
}
