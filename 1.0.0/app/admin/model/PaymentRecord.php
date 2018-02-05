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
 * 文章模型
 * @package app\admin\model
 */
class PaymentRecord extends Model
{
    public static function get_payment_record($where = array())
    {
        return Db::name('payment_records')->where($where)->find();
    }
    public static function get_member_payment_records($where = array())
    {
        return Db::name('payment_records')->alias('pr')
                        ->join(config('database.prefix').'member_list m','m.member_list_id = pr.member_list_id' )
                        ->where($where)
                        ->filed('m.*,pr.pay_type,pr.subject_id,pr.status,pr.order_sn,pr.trade_no,pr.addtime as praddtime,pr.paytime')
                        ->select();
    }
}
