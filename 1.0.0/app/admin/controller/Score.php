<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use app\admin\model\School as SchoolModel;
use app\admin\model\Major as MajorModel;
use app\admin\model\RecruitMajor as RecruitMajorModel;
use app\admin\model\Score as ScoreModel;
use app\admin\model\Subject as SubjectModel;
use app\admin\model\Enrollment as EnrollmentModel;
use app\admin\model\ExaminationMember;
use think\Db;
use think\Cache;
use think\Loader;

class Score extends Base
{
    public function score_list()
    {
        $search_key= trim(input('search_key',''));
        $rooms = Db::name('examination_room')->alias('e')->select();
		$this->assign('rooms',$rooms);
        $map = [];
        if($this->admin['group_id'] == 6)
        {
            $examination_room = Db::name('examination_room')->where(['room_name' => $this->admin['admin_username'] ])->find();
            $map['er.room_id'] = $examination_room['room_id'];
        }
        else if($room_id = input('room_id') ? input('room_id') : $rooms[0]['room_id']){
            $map['er.room_id'] = $room_id;
            $examination_room = Db::name('examination_room')->where(['room_id' => $room_id])->find();
        }
        $subject_id = $examination_room['subject_id'];
        $subjects = SubjectModel::getPCSubjects($subject_id);
        $this->assign('subjects',$subjects);
        $data = Db::name('examination_member')
                                ->alias('em')
                                ->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
                                ->join(config('database.prefix').'member_list m','m.member_list_id = em.member_list_id')
                                ->where($map)
                                ->field('m.member_list_id,m.member_list_nickname , m.member_list_username, m.member_list_id,em.*,er.*')
                                ->select();
        $no = 0;
        foreach($data as $key => $value)
        {
            $no++;
            $score_arr = array();
            foreach ($subjects as $sk => $sv) {
                $score_data = Db::name('score')->where(['subject_id' => $sv['subject_id'],'member_list_id' => $value['member_list_id']])->find();

                $score_arr[$sk]['score'] = $score_data ? $score_data['score'] : '';
                $score_arr[$sk]['subject_id'] = $sv['subject_id'];
            }
            $data[$key]['score_status'] = $score_data['score_status'] ?  $score_data['score_status'] : 0;
            $data[$key]['status_desc'] = config("status")[$data[$key]['score_status']] ;
            $data[$key]['score_arr'] = $score_arr;
            $data[$key]['no'] = $no;
        }
		$this->assign('data',$data);

        $this->assign('search_key',$search_key);
        if(request()->isAjax()){
            return $this->fetch('ajax_score_list');
        }else{
            return $this->fetch();
        }
    }

    public function test()
    {
        $subject_id = 1;
        $subjects = SubjectModel::getPCSubjects($subject_id);
        $examinee_type_id = 1;
        $recruit_major_id = 6;
        $enrollment = Db::name('enrollment')->where(['examinee_type' => $examinee_type_id,'recruit_major_id' => $recruit_major_id])->find();
        // $member = ExaminationMember::getScoreRank($examinee_type_id ,$subjects,$recruit_major_id,$enrollment['enrollment_number'],$subject_id);
        $member = Db::name('member_list')->where(['examinee_type_id' => $examinee_type_id,'recruit_major_id' => $recruit_major_id])->limit(50)->select();
        $data = $room_data = array();
        $room_no = 1;
        foreach($member as $key => $val)
        {
            // if($val['is_pass'])
            // {
                $data[] = array(
                    'pay_type' => 'alipay',
                    'subject_id' => $subject_id,
                    'status' => 'success',
                    'order_sn' => rand(10000,99999).time(),
                    'trade_no' => 'tn' . rand(10000,99999) .time(),
                    'member_list_id' => $val['member_list_id'],
                    'addtime' => time(),
                    'paytime' => time(),
                 );
                 // $room_no++;
                 // $admission_ticket = rand(100000000,9999999999);
                 // $room_data[] = array(
                 //     'recruit_major_id' => $recruit_major_id,
                 //     'room_id' => 10,
                 //     'member_list_id' => $val['member_list_id'],
                 //     'room_no' => $room_no,
                 //     'admission_ticket' => $admission_ticket,
                 // );
             //}
        }
        Db::name('payment_records')->insertAll($data);
        //Db::name('examination_member')->insertAll($room_data);
    }

    public function write_score_rank()
    {
        //专业（面向招生对象）
        $enrollments = Db::name('enrollment')->alias('e')
							->join(config('database.prefix').'recruit_major rm','e.recruit_major_id = rm.recruit_major_id')
                            ->where('examinee_type = 4')
                            ->order('rm.recruit_major_id ASC')
                            ->order('e.enrollment_id ASC')
							->select();

		foreach ($enrollments as $key => $enrollment) {
			$enrollments[$key]['school_type'] = config('school_type')[$enrollment['school_type']];
            $enrollments[$key] = EnrollmentModel::handleEnrollment($enrollment);
		}
        $this->assign('enrollments',$enrollments);

        //从招生计划中获取，默认第一条数据
        $enrollment_id = input('enrollment_id',$enrollments[0]['enrollment_id']);
        $enrollment = EnrollmentModel::get_enrollment($enrollment_id);
        $this->assign('enrollment_id',$enrollment_id);

        $examinee_type_ids = $enrollment['examinee_type'];
        //获取招生计划信息，得到 examinee_type_id,由examinee_type_id判读是否 得出中职，普高，再得出科目数据
        $examinee_type = Db::name('examinee_type')->where("examinee_type_id in (".$examinee_type_ids.")")->find();
        $examinee_type_id = $examinee_type['exaparentid'] ? $examinee_type['exaparentid'] : $examinee_type['examinee_type_id'] ;
        $psubjects = SubjectModel::getPSubjects(['sub.examinee_type_id' => $examinee_type_id]);
        $this->assign('psubjects',$psubjects);
        $subject_id = input('subject_id',6);
        $this->assign('subject_id',$subject_id);
        $subjects = SubjectModel::getPCSubjects($subject_id);
        $this->assign('subjects',$subjects);
        $examinee_type_key = array_keys($enrollment['examinee_type_val']);
        $data = ExaminationMember::getScoreRank($examinee_type_ids,$subjects,$enrollment['recruit_major_id'],$enrollment['enrollment_number'],$subject_id);

        $this->assign('data',$data);

        $write_publicity_setting = Db::name('publicity_setting')->where(['name' => 'write'])->find();
        $starttime = $write_publicity_setting['starttime'];
        $endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
        //$to_endtime = date('Y-m-d',strtotime("+2 day"))." 23:59:59";
        $write_publicity_setting['endtime'] = $endtime;
        $this->assign('write_publicity_setting',$write_publicity_setting);
        if(request()->isAjax()){
            return $this->fetch('ajax_write_score_rank');
        }else{
            return $this->fetch();
        }
    }
    public function enroll_member()
    {
        $enrollments = Db::name('enrollment')->alias('e')
							->join(config('database.prefix').'recruit_major rm','e.recruit_major_id = rm.recruit_major_id')
                            ->order('rm.recruit_major_id ASC')
                            ->order('e.enrollment_id ASC')
							->select();
        foreach ($enrollments as $key => $enrollment) {
			$enrollments[$key]['school_type'] = config('school_type')[$enrollment['school_type']];
            $enrollments[$key] = EnrollmentModel::handleEnrollment($enrollment);
		}
        $this->assign('enrollments',$enrollments);

        //从招生计划中获取，默认第一条数据
        $enrollment_id = input('enrollment_id',$enrollments[0]['enrollment_id']);
        $enrollment = EnrollmentModel::get_enrollment($enrollment_id);
        $this->assign('enrollment_id',$enrollment_id);

        $examinee_type_ids = $enrollment['examinee_type'];
        //获取招生计划信息，得到 examinee_type_id,由examinee_type_id判读是否 得出中职，普高，再得出科目数据
        $examinee_type = Db::name('examinee_type')->where("examinee_type_id in (".$examinee_type_ids.")")->find();
        $examinee_type_id = $examinee_type['exaparentid'] ? $examinee_type['exaparentid'] : $examinee_type['examinee_type_id'] ;
        $subjects = SubjectModel::getCSubjects(['sub.examinee_type_id' => $examinee_type_id]);
        $this->assign('subjects',$subjects);
        $examinee_type_key = array_keys($enrollment['examinee_type_val']);
        $data = ExaminationMember::getAllScoreRank($examinee_type_ids,$subjects,$enrollment['recruit_major_id'],$enrollment['enrollment_number']);
        $this->assign('data',$data);

        $enroll_publicity_setting = Db::name('publicity_setting')->where(['name' => 'enroll'])->find();
        $starttime = $enroll_publicity_setting['starttime'];
        $endtime = $starttime ? date('Y-m-d',strtotime(date('Y-m-d H:i:s',$starttime)." +2 day"))." 23:59:59" : '';
        //$to_endtime = date('Y-m-d',strtotime("+2 day"))." 23:59:59";
        $enroll_publicity_setting['endtime'] = $endtime;
        $this->assign('enroll_publicity_setting',$enroll_publicity_setting);

        if(request()->isAjax()){
            return $this->fetch('ajax_enroll_member');
        }else{
            return $this->fetch();
        }
    }
    public function ajax_enrollment_subjects()
    {
        if (!request()->isAjax()){
			$this->error('提交方式不正确');
		}
        //从招生计划中获取，默认第一条数据
        $enrollment_id = input('enrollment_id');
        $enrollment = EnrollmentModel::get_enrollment($enrollment_id);

        $examinee_type_ids = $enrollment['examinee_type'];
        //获取招生计划信息，得到 examinee_type_id,由examinee_type_id判读是否 得出中职，普高，再得出科目数据
        $examinee_type = Db::name('examinee_type')->where("examinee_type_id in (".$examinee_type_ids.")")->find();
        $examinee_type_id = $examinee_type['exaparentid'] ? $examinee_type['exaparentid'] : $examinee_type['examinee_type_id'] ;
        $subjects = SubjectModel::getPSubjects(['sub.examinee_type_id' => $examinee_type_id]);
        $html = '';
        foreach($subjects as $sk => $sv)
        {
            $html .= "<option value='".$sv['subject_id']."'>".$sv['subject_name']."</option>";
        }
        return [
            'code' => 200,
            'html' => $html,
        ];

    }
    public function score_add()
    {
        $member_list_edit=Db::name('member_list')->where(array('member_list_id'=>input('member_list_id')))->find();
        $major_score_data = Db::name('major_score')->where(array('member_list_id' => input('member_list_id')))->find();
        if($major_score_data)
        {
            $major_score_val = json_decode($major_score_data['major_score'],true);
            $this->assign('major_score_val',$major_score_val);
        }
        $major = MajorModel::get_major_detail($member_list_edit['major_id'],$member_list_edit['school_id']);
		$major_score = $major['score'] ? json_decode($major['score'],true) : [];
		$major_score = array_filter($major_score);
		$this->assign('major_score',$major_score);
        $this->assign('member_list_edit',$member_list_edit);
        return $this->fetch();
    }
    public function score_runadd()
    {
        $member_list_id = input('member_list_id');
        $subject_id = input('subject_id');
        if(!$member_list_id){
            $this->error('参数错误！');
        }
        $score = $_POST['score'];
        $score_data = Db::name('score')->where(array('member_list_id' => $member_list_id,'subject_id' => $subject_id))->find();
        if($score_data)
        {
            if($score_data['score_status'] == 1)
            {
                $this->error('提交失败。已审核通过不能修改');
            }
            $data = [
                'score' => $score,
                'score_status' => 0,
            ];
            $rst = Db::name('score')->where(array('member_list_id' => $member_list_id,'subject_id' => $subject_id))->update($data);
			if($rst!==false){
                $this->success('提交成功，请等待学生打印',url('admin/Score/score_list'));
			}else{
				$this->error('提交失败');
			}
        }
        $data = [
            'member_list_id' => $member_list_id,
            'subject_id' => $subject_id,
            'score' => $score
        ];
        $rst = Db::name('score')->insert($data);
        if($rst!==false){
            $this->success('提交成功
            ',url('admin/Score/score_list'));
        }else{
            $this->error('提交失败');
        }
    }
	public function score_del()
	{
		$p=input('p');
		$rst=Db::name('major_score')->where(array('member_list_id'=>input('member_list_id')))->delete();
		if($rst!==false){
			$this->success('删除成功',url('admin/Score/score_list',array('p' => $p)));
		}else{
			$this -> error("删除失败！",url('admin/Score/score_list',array('p'=>$p)));
		}
	}
    public function score_list_export()
    {
        $major_id = input('major_id','');

        $map['m.major_id'] = $major_id;

        $map['m.school_id'] = $this->admin['school_id'];

        $data = Db::name('major_score')->alias("ms")
                        ->join(config('database.prefix').'member_list m','m.member_list_id = ms.member_list_id','right')
                        ->join(config('database.prefix').'member_info mi','m.member_list_id = mi.member_list_id')
                        ->join(config('database.prefix').'major mj','mj.major_id = m.major_id')
                        ->where($map)
                        ->order('ms.major_score_status desc')
                        ->order('m.member_list_id desc')
                        ->field('ms.major_score, ms.major_score_id,ms.major_score_status,m.member_list_nickname , m.member_list_username, m.member_list_id,mj.major_name,mj.major_name,m.major_id,m.school_id,mi.ZexamineeNumber')
                        ->order('major_score_id desc')->select();

        $status = config("status_title");

        $major = MajorModel::get_major_detail($major_id,$this->admin['school_id']);

        $school = Db::name('school')->where(['school_id' =>$this->admin['school_id'] ])->find();

        $title = $school['school_name'].'  '.$major['major_name'].'   核定理论成绩表';
        $author = '广东农工商职业技术学院   对口';

        $major_score = $major['score'] ? json_decode($major['score'],true) :[];
		$major_score = array_filter($major_score);

        $major_score_key = $major['major_score_key'] ? array_filter(json_decode($major['major_score_key'],true)) : [];

		foreach($data as $key => $val)
		{
            $major_score_arr = json_decode($val['major_score'],true);
            $major_score_desc = major_score_desc($major_score_key,$major_score_arr);
            $major_score_arr = handle_major_score_arr($major_score_key,$major_score_arr);
            $data[$key]['major_score_arr'] = $major_score_arr;
            $data[$key]['major_score_desc'] = $major_score_desc;
            $major_score_status = $val['major_score_status'] ? $val['major_score_status'] : 0 ;
            $data[$key]['major_score_status'] = $major_score_status ;
            $data[$key]['status_desc'] = $status[$major_score_status] ;
            $major_score_total = handle_major_score($major_score_arr);
            $data[$key]['major_score_total'] = $major_score_total;
            // $data[$key]['member_list_username'] = $val['member_list_username']."\t";
            // $data[$key]['ZexamineeNumber'] = $val['ZexamineeNumber']."\t";
            $j = 0;
            foreach ($major_score_arr as $major_score_k => $major_score_v) {
                $data[$key]['major_'.$j] = $major_score_v;
                $j++;
            }
		}
        $field_titles = ['0' => '姓名','1' => '中职考生号','2' => '身份证','3' => '中职专业'];
        $i = 4;
        foreach ($major_score as $k => $major) {
            $field_titles[$i] = $major;
            $i++;
        }
        $field_titles[$i] = '核定理论成绩';
        $field_titles[$i+1] = '审核状态';

        $fields = ['0' => 'member_list_nickname','1' => 'ZexamineeNumber','2' => 'member_list_username','3' => 'major_name','major_score_total','status_desc'];
        $i = 4; $j = 0;
        foreach ($major_score as $k => $major) {
            $fields[$i] = 'major_'.$j;
            $i++;
            $j++;
        }
        $fields[$i] = 'major_score_total';
        $fields[$i+1] = 'status_desc';

        $table = '自主考核理论成绩'.date('YmdHis');

        $this->score_list_export_pdf($field_titles,$fields,$data,$table,$title,$author);
        return false;

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

	public function score_active()
	{
		$p = input('p');
		$ids = input('n_id/a');
		if(empty($ids)){
			$this -> error("请选择列表",url('admin/score/score_all',array('p'=>$p)));
		}
		if(is_array($ids)){
			$where = 'member_list_id in('.implode(',',$ids).')';
		}else{
			$where = 'member_list_id ='.$ids;
		}

		$rst=Db::name('score')->where($where)->setField('score_status',1);
		if($rst!==false){
			foreach($ids as $key => $id)
			{
				$data = Db::name('score')->where(array('member_list_id' => $id))->find();
				if($data){
					Db::name('member_list')->where(array('member_list_id' => $data['member_list_id']))->update(array('major_score' => $data['score']));
				}
			}
			$this->success("操作成功",url('admin/score/score_list',array('p'=>$p)));
		}else{
			$this -> error("操作失败！",url('admin/score/score_list',array('p'=>$p)));
		}
	}
	public function score_unactive()
	{
		$p = input('p');
		$ids = input('n_id/a');
		if(empty($ids)){
			$this -> error("请选择列表",url('admin/score/score_all',array('p'=>$p)));
		}
		if(is_array($ids)){
			$where = 'member_list_id in('.implode(',',$ids).')';
		}else{
			$where = 'member_list_id='.$ids;
		}

		$rst=Db::name('major_score')->where($where)->setField('major_score_status',0);
		if($rst!==false){
			$this->success("操作成功",url('admin/score/score_all',array('p'=>$p)));
		}else{
			$this -> error("操作失败！",url('admin/score/score_all',array('p'=>$p)));
		}
	}

    public function score_list_export_pdf($field_titles=array(),$fields=array(),$data=array(),$fileName='Newfile',$title,$author=''){

		set_time_limit(120);
		if(empty($field_titles) || empty($data)) $this->error("导出的数据为空！");
		require_once(EXTEND_PATH . 'tcpdf/examples/lang/eng.php');
        require_once(EXTEND_PATH . 'tcpdf/ScoreListTCPDF.php');
		$pdf = new \ScoreListTCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);//新建pdf文件
		 //设置文件信息
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor("Author");
		$pdf->SetTitle("pdf test");
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
        //设置页眉页脚
        $pdf->SetHeaderData('', '', $author,$title,array(66,66,66), array(0,0,0));
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);//设置默认等宽字体
        $pdf->SetMargins(5, 24, 5);//设置页面边幅
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(30);
        $pdf->SetAutoPageBreak(TRUE, 40);//设置自动分页符
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->setLanguageArray($l);
        $pdf->SetFont('droidsansfallback', '');
        $pdf->AddPage();

        $pdf->SetFillColor(245, 245, 245);
        $pdf->SetTextColor(0);
        $pdf->SetDrawColor(66, 66, 66);
        $pdf->SetLineWidth(0.3);
        $pdf->SetFont('droidsansfallback', '',9);
        // Header
        $num_headers = count($field_titles);
        for($i = 0; $i < $num_headers; ++$i) {
        	$pdf->MultiCell(280/$num_headers, 8, $field_titles[$i], $border=1, $align='C',1, $ln=0, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='C', $fitcell=true);
        }
        $pdf->Ln();

        // 填充数据
        $fill = 0;
        foreach($data as $list) {
            //每頁重复表格标题行
            if(($pdf->getPageHeight()-$pdf->getY())<($pdf->getBreakMargin()+2)){
                $pdf->AddPage();
                $pdf->SetFillColor(245, 245, 245);
                $pdf->SetTextColor(0);
                $pdf->SetDrawColor(66, 66, 66);
                $pdf->SetLineWidth(0.3);
                $pdf->SetFont('droidsansfallback', '',9);
                // Header
                for($i = 0; $i < $num_headers; ++$i) {
                	$pdf->MultiCell(280/$num_headers, 8, $field_titles[$i], $border=1, $align='C',1, $ln=0, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='C', $fitcell=true);
                }

                $pdf->Ln();

            }
            // Color and font restoration
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetTextColor(40);
            $pdf->SetLineWidth(0.1);
            $pdf->SetFont('droidsansfallback', '');

            foreach($fields as $i=>$name){
				$pdf->MultiCell(280/$num_headers, 6, $list[$name], $border=1, $align='C',$fill, $ln=0, $x='', $y='',  $reseth=true, $stretch=0,$ishtml=false, $autopadding=true, $maxh=0, $valign='C', $fitcell=true);
            }

            $pdf->Ln();
            $fill=!$fill;
        }


		// reset pointer to the last page
		$pdf->lastPage();

        $showType= 'D';//PDF输出的方式。I，在浏览器中打开；D，以文件形式下载；F，保存到服务器中；S，以字符串形式输出；E：以邮件的附件输出。
        $pdf->Output("{$fileName}.pdf", $showType);
        exit;
	}

}
