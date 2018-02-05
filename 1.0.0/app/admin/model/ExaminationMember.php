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

class ExaminationMember extends Model
{
    public static function getScoreRank($examinee_type_ids,$subjects,$recruit_major_id,$enrollment_number,$subject_id)
    {
        $data = Db::name('examination_member')
                                ->alias('em')
                                ->join(config('database.prefix').'examination_room er','er.room_id = em.room_id')
                                ->join(config('database.prefix').'member_list m','m.member_list_id = em.member_list_id')
                                ->where('m.examinee_type_id in ('.$examinee_type_ids.')')
                                ->where("m.recruit_major_id = '".$recruit_major_id."'")
                                ->where("er.subject_id = '".$subject_id."'")
                                ->field('m.member_list_id,m.examinee_type_id,m.member_list_nickname , m.member_list_username, m.member_list_id,em.*,er.*')
                                ->select();
        foreach($data as $key => $value)
        {
            $score_arr = array();
            $write_score_total = 0;
            $is_pass = 1;
            foreach ($subjects as $sk => $sv) {
                $score_data = Db::name('score')->where(['subject_id' => $sv['subject_id'],'member_list_id' => $value['member_list_id']])->find();

                $score_arr[$sk]['score'] = $score_data ? $score_data['score'] : 0;
                $score_arr[$sk]['subject_id'] = $score_data ? $score_data['subject_id'] : '';
                if($score_arr[$sk]['score'] < $sv['pass_mark'])
                {
                    $is_pass = 0;
                }
                $write_score_total += $score_arr[$sk]['score'];
            }
            $data[$key]['score_status'] = $score_data['score_status'] ?  $score_data['score_status'] : 0;
            $data[$key]['status_desc'] = config("status")[$data[$key]['score_status']] ;
            $data[$key]['score_arr'] = $score_arr;
            $data[$key]['is_pass'] = $is_pass;
            $data[$key]['write_score_total'] = $write_score_total;
        }
        array_multisort(array_column($data,'is_pass'),SORT_DESC,array_column($data,'write_score_total'),SORT_DESC,$data);
        $no = 0;
        foreach ($data as $key => $value) {
            $no++;
            $data[$key]['no'] = $no;
            if($no > $enrollment_number * 1.5)
            {
                $data[$key]['is_pass'] = 0;
            }
            $data[$key]['pass_status'] = config('pass_status.'.$data[$key]['is_pass']);
        }
        return $data;
    }
    public static function getAllScoreRank($examinee_type_ids,$subjects,$recruit_major_id,$enrollment_number)
    {
        $data = Db::name('examination_member')
                                ->alias('em')
                                ->join(config('database.prefix').'member_list m','m.member_list_id = em.member_list_id')
                                ->where('m.examinee_type_id in ('.$examinee_type_ids.')')
                                ->where("m.recruit_major_id = '".$recruit_major_id."'")
                                ->field('m.member_list_id,m.examinee_type_id,m.member_list_nickname , m.member_list_username, m.member_list_id,em.*')
                                ->distinct('em.member_list_id')
                                ->group('em.member_list_id')
                                ->select();
        foreach($data as $key => $value)
        {
            $score_arr = array();
            $is_pass = 1;
            $score_total = 0;
            foreach ($subjects as $sk => $sv) {
                $score_data = Db::name('score')->where(['subject_id' => $sv['subject_id'],'member_list_id' => $value['member_list_id']])->find();

                $score_arr[$sk]['score'] = $score_data ? $score_data['score'] : 0;
                $score_arr[$sk]['subject_id'] = $score_data ? $score_data['subject_id'] : '';
                if($score_arr[$sk]['score'] < $sv['pass_mark'])
                {
                    $is_pass = 0;
                }
                $score_total += $score_arr[$sk]['score'];
            }
            $data[$key]['score_total'] = $score_total;
            $data[$key]['is_pass'] = $is_pass;
            $data[$key]['score_arr'] = $score_arr;
        }
        array_multisort(array_column($data,'is_pass'),SORT_DESC,array_column($data,'score_total'),SORT_DESC,$data);
        $no = 0;
        foreach ($data as $key => $value) {
            $no++;
            $data[$key]['no'] = $no;
            if($no > $enrollment_number)
            {
                $data[$key]['is_pass'] = 0;
            }
            $data[$key]['pass_status'] = config('pass_status.'.$data[$key]['is_pass']);
        }
        return $data;
    }
}
