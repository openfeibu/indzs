<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"/home/vagrant/Code/independent/app/admin/view/examination/ajax_member_list.html";i:1511967861;}*/ ?>
<?php if(!empty($members)): if(is_array($members) || $members instanceof \think\Collection || $members instanceof \think\Paginator): if( count($members)==0 ) : echo "" ;else: foreach($members as $key=>$v): ?>
    <tr>
        <td><?php echo $v['room_name']; ?></td>
        <td><?php echo $v['room_no']; ?></td>
        <td><?php echo $v['member_list_nickname']; ?></td>
    </tr>
<?php endforeach; endif; else: echo "" ;endif; else: ?>
    <tr>
        <td colspan="20" align="center">暂无数据</td>
    </tr>
<?php endif; ?>
<tr>
	<td colspan="12" align="center" class="hidden-xs"><?php echo $page; ?></td>
</tr>
