<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/home/vagrant/Code/independent/app/admin/view/score/ajax_score_list.html";i:1512003642;}*/ ?>
<?php if($data): ?>

<tr>
    <th class="hidden-xs ">
        <label class="pos-rel">
            <input type="checkbox" class="ace" id="chkAll" onclick="CheckAll(this.form)" value="全选">
            <span class="lbl"></span>
        </label>
        ID
    </th>
    <th>姓名</th>
    <th>身份证</th>
    <th>考场</th>
    <?php if(is_array($subject['subjects']) || $subject['subjects'] instanceof \think\Collection || $subject['subjects'] instanceof \think\Paginator): if( count($subject['subjects'])==0 ) : echo "" ;else: foreach($subject['subjects'] as $key=>$v): ?>
    <th><?php echo $v; ?></th>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    <th>成绩审核状态</th>
</tr>

<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$v): ?>
<tr>
    <td class="hidden-xs" height="28" >
        <label class="pos-rel">
            <input name="n_id[]" id="navid" class="ace" type="checkbox" value="<?php echo $v['member_list_id']; ?>">
            <span class="lbl"></span>
        </label><?php echo $v['member_list_id']; ?>
    </td>
    <td><?php if($v['score_status'] ==1): ?><?php echo $v['member_list_nickname']; else: ?><a href="<?php echo url('admin/score/score_add',array('member_list_id'=>$v['member_list_id'])); ?>"><?php echo $v['member_list_nickname']; ?></a><?php endif; ?></td>
    <td height="28" ><?php echo $v['member_list_username']; ?></td>
    <td><?php echo $v['room_name']; ?></td>
    <?php if(is_array($v['score_arr']) || $v['score_arr'] instanceof \think\Collection || $v['score_arr'] instanceof \think\Paginator): if( count($v['score_arr'])==0 ) : echo "" ;else: foreach($v['score_arr'] as $key=>$val): ?>
    <td data-id="<?php echo $v['member_list_id']; ?>"><input name="score[]" value="<?php echo $val; ?>" <?php if($v['score_status']==1): ?>disabled<?php endif; ?> class="major_score score_input" > </td>
    <?php endforeach; endif; else: echo "" ;endif; ?>
    </td>
    <td class="status_<?php echo $v['member_list_id']; ?>"><?php echo $v['status_desc']; ?></td>
</tr>
<?php endforeach; endif; else: echo "" ;endif; ?>
<tr>
    <td height="50" align="center">
        <button id="btnsubmit" class="btn btn-white btn-blue btn-sm hidden-xs btn-active">通过审核</button>
    </td>
    <td height="50" colspan="20" align="center"><?php echo $page; ?></td>
</tr>
<?php else: ?>
<tr>
    <td height="50" colspan="20" align="center">暂无数据</td>
</tr>
<?php endif; ?>
