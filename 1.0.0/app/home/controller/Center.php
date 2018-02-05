<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\home\controller;

use think\Db;
use app\admin\model\MemberList;
use app\admin\model\Subject;
use app\admin\model\RecruitMajor as RecruitMajorModel;
use app\admin\model\Major as MajorModel;
use app\admin\model\PaymentRecord;
use app\admin\model\Subject as SubjectModel;
use app\admin\model\ExaminationMember;

class Center extends Base
{
	protected function _initialize()
    {
		parent::_initialize();
		$this->check_login();
		$this->assign($this->user);
	}
	public function index()
    {
		$info = MemberList::getMember($this->user['member_list_id']);
		$time = time();
		$date = date('  Y  年  m  月  d  日',$time);
		$num=str_pad($this->user['member_list_id'],4,"0",STR_PAD_LEFT);
		$this->assign('num',$num);

		//$recruit_major = RecruitMajorModel::get_recruit_major($this->user['school_id'],$this->user['major_id']);
		$school_type_name = config('school_type.'.$info['school_type']);
		//$this->assign('recruit_major',$recruit_major);
		$recruit_majors =  RecruitMajorModel::get_recruit_majors();
		$this->assign('recruit_majors',$recruit_majors);
		$this->assign('info',$info);
		$this->assign('date',$date);
		$this->assign('school_type_name',$school_type_name);
		$recruit_major_id_arr = explode(',',$info['recruit_major_id']);
		$this->assign('recruit_major_id_arr',$recruit_major_id_arr);
		$member_recruit_major = array();
		foreach ($recruit_major_id_arr as $key => $value) {
			$recruit_major_name = RecruitMajorModel::get_recruit_major_value($value);
			$member_recruit_major[$key]['recruit_major_id'] = $value;
			$member_recruit_major[$key]['recruit_major_name'] = $recruit_major_name;
		}
		//var_dump($recruit_major_id_arr);exit;
		$this->assign('member_recruit_major',$member_recruit_major);

		return $this->view->fetch('user:center');
    }
	public function examination_room()
	{
		return $this->view->fetch('user:examination_room');
	}
	public function examination_flow()
	{
		if($this->user['examinee_type_id'] == 3)
		{

		}
		else
		{
			//是否已报名
			$is_apply = Db::name('member_info')->where(['member_list_id' => $this->user['member_list_id']])->find();
			$this->assign('is_apply',$is_apply);
			//笔试缴费
			$write_pay = PaymentRecord::get_payment_record(array('member_list_id' => $this->user['member_list_id'],'subject_id' => '6','status' => 'success'));
			$this->assign('write_pay',$write_pay);
			//笔试准考证
			$wirte_ticket = Db::name('examination_member')->alias('em')
							->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
							->where(['em.member_list_id' => $this->user['member_list_id'],'er.subject_id' => '6'])
							->field('em.room_no,em.room_id,em.admission_ticket')
							->find();
			$this->assign('wirte_ticket',$wirte_ticket);
			//笔试成绩
			$write_score_arr = Db::name('score')->where('subject_id','in','3,4')->where(['member_list_id' => $this->user['member_list_id'],'score_status' => 1])->select();
			$this->assign('write_score_arr',$write_score_arr);
			//笔试公示结果
			$write_publicity_setting = Db::name('publicity_setting')->where(['name' => 'write'])->find();
	        $starttime = $write_publicity_setting['starttime'];
			$endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
			$write_publicity_setting['endtime'] = $endtime;
			$time = time();
			$is_write_publicity = $is_writing_publicity = 0;
			if($starttime)
			{
				//是否公示
				$is_write_publicity = 1;
				if($time<=strtotime($endtime))
				{
					//是否公示中
					$is_writing_publicity = 1;
				}
			}
			$this->assign('is_write_publicity',$is_write_publicity);
			$this->assign('is_writing_publicity',$is_writing_publicity);
			$this->assign('write_publicity_setting',$write_publicity_setting);


			//笔试成绩

			$subject_id = 6;
			$subjects = SubjectModel::getPCSubjects($subject_id);
			$examinee_type_id = 4;
			$enrollment = Db::name('enrollment')->where(['examinee_type' => $examinee_type_id,'recruit_major_id' => $this->user['recruit_major_id']])->find();
			$write_score_data = ExaminationMember::getScoreRank($examinee_type_id ,$subjects,$this->user['recruit_major_id'],$enrollment['enrollment_number'],$subject_id);
			foreach ($write_score_data as $wkey => $wvalue) {
				if($wvalue['member_list_id'] == $this->user['member_list_id'])
				{
					$this->user['write_pass'] = $wvalue['is_pass'];
				}
			}

			//技能考核缴费
			$skill_pay = PaymentRecord::get_payment_record(array('member_list_id' => $this->user['member_list_id'],'subject_id' => '5','status' => 'success'));
			$this->assign('skill_pay',$skill_pay);

			//技能考核准考证
			$skill_ticket = Db::name('examination_member')->alias('em')
							->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
							->where(['em.member_list_id' => $this->user['member_list_id'],'er.subject_id' => '5'])
							->field('em.room_no,em.room_id,em.admission_ticket')
							->find();
			$this->assign('skill_ticket',$skill_ticket);

			//技能考核成绩
			$skill_score_arr = Db::name('score')->where('subject_id','in','5')->where(['member_list_id' => $this->user['member_list_id'],'score_status' => 1])->select();
			$this->assign('skill_score_arr',$skill_score_arr);

			//录取公示结果
			$enroll_publicity_setting = Db::name('publicity_setting')->where(['name' => 'enroll'])->find();
	        $starttime = $enroll_publicity_setting['starttime'];
			$endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
			$enroll_publicity_setting['endtime'] = $endtime;
			$time = time();
			$is_enroll_publicity = $is_enrolling_publicity = 0;
			if($starttime)
			{
				//是否公示
				$is_enroll_publicity = 1;
				if($time<=strtotime($endtime))
				{
					//是否公示中
					$is_enrolling_publicity = 1;
				}
			}
			$this->assign('is_enroll_publicity',$is_enroll_publicity);
			$this->assign('is_enrolling_publicity',$is_enrolling_publicity);
			$this->assign('enroll_publicity_setting',$enroll_publicity_setting);


			$subjects = SubjectModel::getCSubjects(['sub.examinee_type_id' => $examinee_type_id]);
			$enroll_score_data = ExaminationMember::getAllScoreRank($examinee_type_id ,$subjects,$this->user['recruit_major_id'],$enrollment['enrollment_number']);
			foreach ($enroll_score_data as $ekey => $evalue) {
				if($evalue['member_list_id'] == $this->user['member_list_id'])
				{
					$this->user['enroll_pass'] = $evalue['is_pass'];
				}
			}
			$this->assign('user',$this->user);
		}

		return $this->view->fetch('user:examination_flow');
	}
	public function examination_write_ticket()
	{
		$wirte_ticket = Db::name('examination_member')->alias('em')
						->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
						->where(['em.member_list_id' => $this->user['member_list_id'],'er.subject_id' => '6'])
						->field('em.room_no,em.room_id,em.admission_ticket,er.room_name')
						->find();
		$wirte_ticket['room_no'] = sprintf("%03d", $wirte_ticket['room_no']);
		$this->assign('wirte_ticket',$wirte_ticket);
		$info = MemberList::getMember($this->user['member_list_id']);
		$this->assign('info',$info);
		return $this->view->fetch('user:examination_write_ticket');
	}
	public function examination_skill_ticket()
	{
		$skill_ticket = Db::name('examination_member')->alias('em')
						->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
						->where(['em.member_list_id' => $this->user['member_list_id'],'er.subject_id' => '5'])
						->field('em.room_no,em.room_id,em.admission_ticket,er.room_name')
						->find();
		$wirte_ticket['room_no'] = sprintf("%03d", $skill_ticket['room_no']);
		$this->assign('skill_ticket',$skill_ticket);
		$info = MemberList::getMember($this->user['member_list_id']);
		$this->assign('info',$info);
		return $this->view->fetch('user:examination_skill_ticket');
	}
	public function grade()
	{
		$subjects = SubjectModel::getCSubjects(['sub.examinee_type_id' => 4]);
		$score_arr = array();
		$total_score = 0;
		foreach ($subjects as $sk => $sv) {
			$score_data = Db::name('score')->where(['subject_id' => $sv['subject_id'],'member_list_id' => $this->user['member_list_id']])->find();
			$score_arr[$sk]['score'] = $score = $score_data ? $score_data['score'] : '';
			$score_arr[$sk]['subject_id'] = $sv['subject_id'];
			$score_arr[$sk]['subject_name'] = $sv['subject_name'];
			$total_score += $score ? $score : 0;
		}
		$this->assign('total_score',$total_score);
		$this->assign('subjects',$subjects);
		$this->assign('score_arr',$score_arr);

		return $this->view->fetch('user:grade');
	}
	public function write_publicity()
	{
		//笔试公示结果
		$write_publicity_setting = Db::name('publicity_setting')->where(['name' => 'write'])->find();
        $starttime = $write_publicity_setting['starttime'];
		$endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
		$write_publicity_setting['endtime'] = $endtime;
		$time = time();
		$is_write_publicity = $is_writing_publicity = 0;
		if($starttime)
		{
			//是否公示
			$is_write_publicity = 1;
			if($time<=$endtime)
			{
				//是否公示中
				$is_writing_publicity = 1;
			}else{

			}
		}else{
			$this->error('非公示时间');
		}
		$subject_id = 6;
        $this->assign('subject_id',$subject_id);
        $subjects = SubjectModel::getPCSubjects($subject_id);
        $this->assign('subjects',$subjects);
		$examinee_type_id = 4;
		$enrollment = Db::name('enrollment')->where(['examinee_type' => $examinee_type_id,'recruit_major_id' => $this->user['recruit_major_id']])->find();
		$data = ExaminationMember::getScoreRank($examinee_type_id ,$subjects,$this->user['recruit_major_id'],$enrollment['enrollment_number'],$subject_id);
		$this->assign('data',$data);
		return $this->view->fetch('user:write_publicity');
	}
	public function enroll_publicity()
	{
		//笔试公示结果
		$enroll_publicity_setting = Db::name('publicity_setting')->where(['name' => 'write'])->find();
        $starttime = $enroll_publicity_setting['starttime'];
		$endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
		$enroll_publicity_setting['endtime'] = $endtime;
		$time = time();
		$is_enroll_publicity = $is_enroll_publicity = 0;
		if($starttime)
		{
			//是否公示
			$is_enroll_publicity = 1;
			if($time<=$endtime)
			{
				//是否公示中
				$is_enrolling_publicity = 1;
			}else{

			}
		}else{
			$this->error('非公示时间');
		}
		$examinee_type_id = 4;
		$subjects = SubjectModel::getCSubjects(['sub.examinee_type_id' => $examinee_type_id]);
        $this->assign('subjects',$subjects);
		$enrollment = Db::name('enrollment')->where(['examinee_type' => $examinee_type_id,'recruit_major_id' => $this->user['recruit_major_id']])->find();
		$data = ExaminationMember::getAllScoreRank($examinee_type_id ,$subjects,$this->user['recruit_major_id'],$enrollment['enrollment_number']);
		$this->assign('data',$data);
		return $this->view->fetch('user:enroll_publicity');
	}
	/*
	public function confirm_grade()
	{
		$where = array('member_list_id' => $this->user['member_list_id']);
		$data = Db::name('major_score')->where($where)->find();
		if($data && (!$this->user['major_score'] && !$this->user['recruit_score'])){
			$rst = Db::name('major_score')->where($where)->setField(['major_score_status' => 1,'recruit_score_status' => 1]);
			Db::name('member_list')->where($where)->update(array('major_score' => $data['major_score'],'recruit_score' => $data['recruit_score']));
		}
		return [
			'code' => 200,
			'msg'  => '操作成功',
		];
	}*/
	public function setting()
	{
		$this->assign($this->user);
		return $this->view->fetch('user:setting');
	}
    //编辑用户资料
	public function edit()
    {
		$province = Db::name('Region')->where ( array('pid'=>1) )->select ();
		$city=Db::name('Region')->where ( array('pid'=>$this->user['member_list_province']) )->select ();
		$town=Db::name('Region')->where ( array('pid'=>$this->user['member_list_city']) )->select ();
		$this->assign('province',$province);
		$this->assign('city',$city);
		$this->assign('town',$town);
		$this->assign($this->user);
		return $this->view->fetch('user:edit');
    }
    public function runedit()
    {
    	if(request()->isPost()){
			$post=input('post.');
			$rst=Db::name('member_list')->where(array('member_list_id'=>$this->user['member_list_id']))->update($post);
			if ($rst!==false) {
				$this->user=Db::name('member_list')->find($this->user['member_list_id']);
				session('user',$this->user);
				$this->success(lang('save success'),url("home/Center/edit"));
			} else {
				$this->error(lang('save failed'));
			}
    	}
    }
	//修改密码
	public function password()
    {
		$this->assign($this->user);
		return $this->view->fetch('user:password');
    }
	public function runchangepwd()
    {
    	if (request()->isPost()) {
			$old_password=input('old_password');
    		$password=input('password');
			$repassword=input('repassword');
    		if(empty($old_password)){
    			$this->error(lang('old pwd empty'));
    		}
    		if(empty($password)){
    			$this->error(lang('new pwd empty'));
    		}
			if($password!==$repassword){
    			$this->error(lang('pwd not same'));
    		}
			$member=Db::name('member_list');
    		$user=$member->where(array('member_list_id'=>$this->user['member_list_id']))->find();
			$member_list_salt=$user['member_list_salt'];
    		if(encrypt_password($old_password,$member_list_salt)===$user['member_list_pwd']){
				if(encrypt_password($password,$member_list_salt)==$user['member_list_pwd']){
					$this->error(lang('new pwd the same as old pwd'));
				}else{
					$data['member_list_pwd']=encrypt_password($password,$member_list_salt);
					$data['member_list_id']=$this->user['member_list_id'];
					$rst=$member->update($data);
					if ($rst!==false) {
						$this->success(lang('revise success'),url('home/Center/index'));
					} else {
						$this->error(lang('revise failed'));
					}
				}
    		}else{
    			$this->error(lang('old pwd not correct'));
    		}
    	}
    }
	public function avatar()
    {
        $imgurl=input('imgurl');
        //去'/'
        $imgurl=str_replace('/','',$imgurl);
        $rst=Db::name('member_list')->where(array('member_list_id'=>$this->user['member_list_id']))->update(array('member_list_headpic'=>$imgurl));
        if($rst!==false){
            session('user_avatar',$imgurl);
			$this->user['member_list_headpic']=$imgurl;
			$url='/data/upload/avatar/'.$imgurl;
			//写入数据库
			$data['uptime']=time();
			$data['filesize']=filesize('./'.$url);
			$data['path']=$url;
			Db::name('plug_files')->insert($data);
            $this->success (lang('avatar update success'),url('home/Center/index'));
        }else{
            $this->error (lang('avatar update failed'),url('home/Center/index'));
        }
    }
    public function bang()
    {
    	$oauth_user_model=Db::name("OauthUser");
    	$oauths=$oauth_user_model->where(array("uid"=>$this->user['member_list_id']))->select();
    	$new_oauths=array();
    	foreach ($oauths as $oa){
    		$new_oauths[strtolower($oa['oauth_from'])]=$oa;
    	}
    	$this->assign("oauths",$new_oauths);
		return $this->view->fetch('user:bang');
    }
    public function fav()
    {
		$favorites_model=Db::name("favorites");
        $favorites=$favorites_model->alias("a")->join(config('database.prefix').'news b','a.t_id =b.n_id')->where(array('uid'=>$this->user['member_list_id']))->order('a.id asc')->paginate(config('paginate.list_rows'));
		$show=$favorites->render();
		$this->assign('page',$show);
		$this->assign("favorites",$favorites);
		return $this->view->fetch('user:favorite');
	}
    public function delete_favorite()
    {
        $id=input("id",0,"intval");
        $p=input("p",1,"intval");
        $favorites_model=Db::name("favorites");
        $result=$favorites_model->where(array('id'=>$id,'uid'=>$this->user['member_list_id']))->delete();
        if($result){
            $this->success(lang('cancel collection success'),url('home/Center/fav',array('p'=>$p)));
        }else {
            $this->error(lang('cancel collection failed'));
        }
    }
	public function update_info()
	{
		$info = $this->getMemberInfo();
		$name = input('name');
		$value = input('value');
		if($name == 'member_list_nickname')
		{
			Db::name('member_list')->where(array('member_list_id'=>$this->user['member_list_id']))->update(array('member_list_nickname' => $value));
			return '';
		}
		if($name == 'recruit_major_id' )
		{
			Db::name('member_list')->where(array('member_list_id'=>$this->user['member_list_id']))->update(array('recruit_major_id' => $value));
			return '';
		}
		if(strstr($name,'certificate'))
		{
			$type = "certificate";
		}
		else if(strstr($name,'prize'))
		{
			$type = "prize";
		}
		else if(strstr($name,'resume'))
		{
			$type = "resume";
		}
		else if(strstr($name,'family')){
			$type = "family";
		}
		if(isset($type))
		{
			$arr = $info[$type];
			$vaArr = explode('_',$name);
			$arr[$vaArr['1']][$vaArr['0']] = $value;
			$name = $type;
			$value = json_encode($arr);
		}
		if(strstr($name,'wat'))
		{
			$arr = $info['watGrade'];
			$arr[$name] = $value;
			$name = 'watGrade';
			$value = json_encode($arr);
		}
		if(isset($info['member_list_id']) && $info['member_list_id'])
		{
			Db::name('member_info')->where(array('member_list_id'=>$this->user['member_list_id']))->update(array($name => $value));
		}else{
			Db::name('member_info')->insert(array($name => $value,'member_list_id' => $this->user['member_list_id']));
		}
	}
	private function getMemberInfo()
	{
		$info = Db::name('member_info')->where(array('member_list_id'=>$this->user['member_list_id']))->find();
		$info['name'] = $this->user['member_list_nickname'];
		$info['cardId'] = $this->user['member_list_username'];
		$info['certificate'] = isset($info['certificate']) && $info['certificate'] ? json_decode($info['certificate'],true) : [];
		$info['resume'] = isset($info['resume']) && $info['resume'] ? json_decode($info['resume'],true) : [];
		$info['prize'] = isset($info['prize']) && $info['prize'] ? json_decode($info['prize'],true) : [];
		$info['family'] = isset($info['family']) && $info['family'] ? json_decode($info['family'],true) : [];
		$info['watGrade'] = isset($info['watGrade']) && $info['watGrade'] ? json_decode($info['watGrade'],true) : config('watGrade');
		return $info;
	}

}
