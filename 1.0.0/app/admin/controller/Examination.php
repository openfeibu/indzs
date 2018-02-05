<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\Admin as AdminModel;
use app\admin\model\Subject as SubjectModel;
use app\admin\model\ExaminationRoom;
use app\admin\model\ExamineeType;
use think\Db;
use think\Cache;

class Examination extends Base
{
    public function rooms()
    {
        $rooms = ExaminationRoom::getAllRooms();
		$this->assign('rooms',$rooms);
        return $this->fetch();
    }
    public function room_add()
    {
        $recruit_major_list = Db::name('recruit_major')->select();
        $this->assign('recruit_major_list',$recruit_major_list);
        $subjects = SubjectModel::getPSubjects();
        $this->assign('subjects',$subjects);
        return $this->fetch();
    }
    public function room_runadd()
    {
        $data = [
            'room_name' => input('room_name'),
            'room_seating' => intval(input('room_seating')),
            'subject_id' => input('subject_id'),
        ];
        Db::name('examination_room')->insert($data);

        $this->success('添加成功',url('admin/Examination/rooms'));
    }
    public function room_edit()
    {
        $room_id = input('room_id');
        $room = Db::name('examination_room')->find($room_id);
        $this->assign('room',$room);
        $recruit_major_list = Db::name('recruit_major')->select();
        $this->assign('recruit_major_list',$recruit_major_list);
        $subjects = SubjectModel::getPSubjects();
        $this->assign('subjects',$subjects);
        return $this->fetch();
    }
    public function room_runedit()
    {
        $room_id = input('room_id','0');
        $room = Db::name('examination_room')->where(array('room_id' => $room_id))->find();
        if(!$room)
        {
            $this->error('数据不存在',url('admin/Examination/rooms'));
        }
        $data = [
            'room_name' => input('room_name'),
            'room_seating' => intval(input('room_seating')),
            'subject_id' => input('subject_id'),
        ];
        $srt = Db::name('examination_room')->where(array('room_id' => $room_id))->update($data);
        $this->success('更新成功',url('admin/Examination/rooms'));
    }
    public function room_del()
    {
        $p=input('p');
        $rst = Db::name('examination_room')->where(array('room_id'=>input('room_id')))->delete();
        if($rst!==false){
            $this->success('删除成功',url('admin/Examination/rooms',array('p' => $p)));
        }else{
            $this -> error("删除失败！",url('admin/Examination/rooms',array('p'=>$p)));
        }
    }
    public function room_alldel()
    {
        $p = input('p');
		$ids = input('n_id/a');
		if(empty($ids)){
			$this -> error("请选择删除考场",url('admin/Examination/rooms',array('p'=>$p)));//判断是否选择了文章ID
		}
		if(is_array($ids)){//判断获取文章ID的形式是否数组
			$where = 'room_id in('.implode(',',$ids).')';
		}else{
			$where = 'room_id='.$ids;
		}
		$rst = Db::name('examination_room')->where($where)->delete();
		if($rst!==false){
			$this->success("成功把考场删除！",url('admin/Examination/rooms',array('p'=>$p)));
		}else{
			$this -> error("考场删除失败！",url('admin/Examination/rooms',array('p' => $p)));
		}
    }
    public function generate_admin()
    {
        $rooms = ExaminationRoom::getAllRooms();
        $group_id = 6;
        $admins = Db::name('admin')->alias('a')->join(config('database.prefix').'auth_group_access aga','aga.uid = a.admin_id')->where(array('aga.group_id' => $group_id))->field('a.admin_id')->select();
        //Db::name('examination_member')->delete(true);
        foreach ($admins as $key => $admin) {
            $member_id=Db::name('admin')->where('admin_id',$admin['admin_id'])->value('member_id');
    		Db::name('admin')->delete($admin['admin_id']);
    		//删除对应学生
    		if($member_id){
    			Db::name('member_list')->delete($member_id);
    		}
    		$rst=Db::name('auth_group_access')->where('uid',$admin['admin_id'])->delete();
        }
        foreach($rooms as $key => $room)
        {
        	$admin_id = AdminModel::add($room['room_name'],'','123456',input('admin_email',''),input('admin_tel',''),input('admin_open',1),input('admin_realname',''),6);
            // $members  = Db::name('member_list')->where(array('recruit_major_id' => $room['recruit_major_id']))->field("member_list_id,'".$room['room_id']."' as room_id")->select();
            // $num = 0;
            // foreach ($members as $k => $v) {
            //     $num++;
            //     $members[$k]['room_no'] = sprintf("%03d", $num);
            // }
            // Db::name('examination_member')->insertAll($members);
        }
        $data = [
            'msg' => '操作成功',
            'status' => 1,
        ];
        return $data;
    }
    public function generate_member()
    {
        //先确定需要考试内容，普高（面试），中职（笔试，实操）
        $subject_id = input('subject_id');
        $subject = SubjectModel::getSubject(['subject_id' => $subject_id]);
        $examinee_type_id = $subject['examinee_type_id'];
    //    $examinee_type = ExamineeType::getExamineeType($examinee_type_id);
        //var_dump($examinee_type);exit;

        //由subject_id先确定已缴费的学生

        //中职，按志愿分类，得出各志愿分类人数，从大到小排列
        if($examinee_type_id == 4)
        {
            $payment_member_list =  Db::name('payment_records')->alias('pr')
                                        ->join(config('database.prefix').'member_list m','m.member_list_id = pr.member_list_id')
                                        ->join(config('database.prefix').'recruit_major rm','rm.recruit_major_id = m.recruit_major_id')
                                        ->where(['pr.subject_id' => $subject_id,'status' => 'success'])
                                        ->field('m.*,rm.recruit_major_name,pr.pay_type,pr.subject_id,pr.status,pr.order_sn,pr.trade_no,pr.addtime as praddtime,pr.paytime')
                                        ->group('pr.record_id')
                                        ->order('record_id desc')
                                        ->select();
            // var_dump($payment_member_list);exit;
        }
        //得出考场容纳数，按大到小排列，



        Db::name('examination_member')->delete(true);
        $data = [
            'code' => 2,
            'msg' => 'ceshi',
        ];
        return $data;
    }
    public function member_list()
    {
        $room_id = input('room_id','0');
        $where = ['m.user_status' => 1];
        if($room_id)
        {
            $where['em.room_id'] = $room_id;
        }
        $members = Db::name('member_list')->alias('m')
                                          ->join(config('database.prefix').'examination_member em','em.member_list_id = m.member_list_id')
                                          ->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
                                          ->where($where)
                                          ->order('er.room_id asc')
                                          ->order('em.id asc')
                                          ->paginate(50,false,['query'=>get_query()]);

        $this->assign('members',$members);
        $rooms = Db::name('examination_room')->alias('e')->select();
		$this->assign('rooms',$rooms);
        $show=$members->render();
		$show=preg_replace("(<a[^>]*page[=|/](\d+).+?>(.+?)<\/a>)","<a href='javascript:ajax_page($1);'>$2</a>",$show);
        $this->assign('page',$show);

        $room_subjects = ExaminationRoom::getRoomSubjects();
        $this->assign('room_subjects',$room_subjects);

        if(request()->isAjax()){
			return $this->fetch('ajax_member_list');
		}else{
			return $this->fetch();
		}
    }
    public function member_export()
	{
        $room_id = input('room_id','0');
        $where = ['m.user_status' => 1];
        if($room_id)
        {
            $where['em.room_id'] = $room_id;
        }
        $data = Db::name('member_list')->alias('m')
                                          ->join(config('database.prefix').'examination_member em','em.member_list_id = m.member_list_id')
                                          ->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
                                          ->join(config('database.prefix').'member_info mi','mi.member_list_id = m.member_list_id')
                                          ->where($where)
                                          ->order('er.room_id asc')
                                          ->order('em.id asc')
                                          ->select();

          $field_titles = ['考场','编号','姓名','考生号'];
          $fields = ['room_name','room_no','member_list_nickname','GexamineeNumber'];
          $table = '考场安排'.date('YmdHis');
          error_reporting(E_ALL);
          date_default_timezone_set('Asia/chongqing');
          $objPHPExcel = new \PHPExcel();
          //import("Org.Util.PHPExcel.Reader.Excel5");
          /*设置excel的属性*/
          $objPHPExcel->getProperties()->setCreator("wuzhijie")//创建人
          ->setLastModifiedBy("wuzhijie")//最后修改人
          ->setKeywords("excel")//关键字
          ->setCategory("result file");//种类

          //第一行数据
          $objPHPExcel->setActiveSheetIndex(0);
          $active = $objPHPExcel->getActiveSheet();
          foreach($field_titles as $i=>$name){
              $ck = num2alpha($i++) . '1';
              $active->setCellValue($ck, $name);
          }
          //填充数据
          foreach($data as $k => $v){
              $k=$k+1;
              $num=$k+1;//数据从第二行开始录入
              $objPHPExcel->setActiveSheetIndex(0);
              foreach($fields as $i=>$name){
                  $ck = num2alpha($i++) . $num;
                  $active->setCellValue($ck, $v[$name]);
              }
          }
          $objPHPExcel->getActiveSheet()->setTitle($table);
          $objPHPExcel->setActiveSheetIndex(0);
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="'.$table.'.xls"');
          header('Cache-Control: max-age=0');
          $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          $objWriter->save('php://output');
          exit;
    }
}
