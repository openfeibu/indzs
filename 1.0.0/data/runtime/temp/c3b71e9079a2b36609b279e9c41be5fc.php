<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:69:"/home/vagrant/Code/independent/app/admin/view/member/member_edit.html";i:1511937113;s:62:"/home/vagrant/Code/independent/app/admin/view/public/base.html";i:1511012885;s:64:"/home/vagrant/Code/independent/app/admin/view/public/header.html";i:1511012885;s:66:"/home/vagrant/Code/independent/app/admin/view/public/left_nav.html";i:1511012885;s:66:"/home/vagrant/Code/independent/app/admin/view/public/head_nav.html";i:1511012885;s:64:"/home/vagrant/Code/independent/app/admin/view/public/footer.html";i:1511012885;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title>三二分段招生管理系统</title>

	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	<link rel="Bookmark" href="__ROOT__/favicon.ico" >
    <link rel="Shortcut Icon" href="__ROOT__/favicon.ico" />
	<!-- bootstrap & fontawesome必须的css -->
	<link rel="stylesheet" href="__PUBLIC__/ace/css/bootstrap.min.css" />
	<link rel="stylesheet" href="__PUBLIC__/font-awesome/css/font-awesome.min.css" />
	<link rel="stylesheet" href="__PUBLIC__/datePicker/bootstrap-datepicker.css" />
	<link rel="stylesheet" href="__PUBLIC__/datetimepicker/bootstrap-datetimepicker.css" />
	<!-- 此页插件css -->

	<!-- ace的css -->
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
	<!-- IE版本小于9的ace的css -->
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace-part2.min.css" class="ace-main-stylesheet" />
	<![endif]-->

	<!--[if lte IE 9]>
	<link rel="stylesheet" href="__PUBLIC__/ace/css/ace-ie.css" />
	<![endif]-->

	<link rel="stylesheet" href="__PUBLIC__/yfcmf/yfcmf.css" />
	<!-- 此页相关css -->

	<!-- ace设置处理的js -->
	<script src="__PUBLIC__/ace/js/ace-extra.js"></script>
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

	<!--[if lte IE 8]>
	<script src="__PUBLIC__/others/html5shiv.min.js"></script>
	<script src="__PUBLIC__/others/respond.min.js"></script>
	<![endif]-->
    <!-- 引入基本的js -->
    <script type="text/javascript">
        var admin_ueditor_handle = "<?php echo url('admin/Ueditor/upload'); ?>";
        var admin_ueditor_lang ='zh-cn';
    </script>
    <!--[if !IE]> -->
    <script src="__PUBLIC__/others/jquery.min-2.2.1.js"></script>
    <!-- <![endif]-->
    <!-- 如果为IE,则引入jq1.12.1 -->
    <!--[if IE]>
    <script src="__PUBLIC__/others/jquery.min-1.12.1.js"></script>
    <![endif]-->

    <!-- 如果为触屏,则引入jquery.mobile -->
    <script type="text/javascript">
        if('ontouchstart' in document.documentElement) document.write("<script src='__PUBLIC__/others/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="__PUBLIC__/others/bootstrap.min.js"></script>
	<style>
	.table-hover > tbody > tr:hover > td,
	.table-hover > tbody > tr:hover > th {
	  background-color: #ccccef;
	}
	</style>
</head>

<body class="no-skin">
<!-- 导航栏开始 -->
<div id="navbar" class="navbar navbar-default navbar-fixed-top printHide">
	<div class="navbar-container" id="navbar-container">
		<!-- 导航左侧按钮手机样式开始 -->
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
			<span class="sr-only">Toggle sidebar</span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>

			<span class="icon-bar"></span>
		</button><!-- 导航左侧按钮手机样式结束 -->
		<button data-target="#sidebar2" data-toggle="collapse" type="button" class="pull-left navbar-toggle collapsed">
			<span class="sr-only">Toggle sidebar</span>
			<i class="ace-icon fa fa-dashboard white bigger-125"></i>
		</button>
		<!-- 导航左侧正常样式开始 -->
		<div class="navbar-header pull-left">
			<!-- logo -->
			<a href="<?php echo url('admin/Index/index'); ?>" class="navbar-brand" title="管理后台首页">
				<small>
					<i class="fa fa-leaf"></i>
					三二分段招生管理系统
				</small>
			</a>
			<span class="admin_title_span"><?php echo $admin['title']; ?> <?php echo $head_title; ?></span>
		</div><!-- 导航左侧正常样式结束 -->

		<!-- 导航栏开始 -->
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="grey">
					<a href="<?php echo url('home/Index/index'); ?>" target="_blank">
						前台首页
					</a>
				</li>
				<?php if($admin['id'] == 1 || $admin['id'] == 5): ?>
				<li class="purple">
					<a data-info="确定要清理缓存吗？" class="confirm-rst-btn" href="<?php echo url('admin/Sys/clear'); ?>">
						清除缓存
					</a>
				</li>
				<?php endif; ?>
				<!-- 用户菜单开始 -->
				<li class="light-blue dropdown-modal">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle">
						<img class="nav-user-photo" src="<?php echo get_imgurl($admin_avatar,2); ?>" alt="<?php echo session('admin_auth.admin_username'); ?>" />
								<span class="user-info">
									欢迎您,	<?php echo session('admin_auth.admin_username'); ?>
								</span>

						<i class="ace-icon fa fa-caret-down"></i>
					</a>

					<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="<?php echo url('admin/Admin/profile'); ?>">
								<i class="ace-icon fa fa-user"></i>
								个人中心
							</a>
						</li>

						<li class="divider"></li>

						<li>
							<a href="<?php echo url('admin/Login/logout'); ?>"  data-info="你确定要退出吗？" class="confirm-btn">
								<i class="ace-icon fa fa-power-off"></i>
								注销
							</a>
						</li>
					</ul>
				</li><!-- 用户菜单结束 -->
			</ul>
		</div><!-- 导航栏结束 -->
	</div><!-- 导航栏容器结束 -->
</div><!-- 导航栏结束 -->


<!-- 整个页面内容开始 -->
<div class="main-container" id="main-container">
	<!-- 菜单栏开始 -->
	<div id="sidebar" class="sidebar responsive sidebar-fixed ace-save-state printHide">
	<script type="text/javascript">
		try{ace.settings.loadState('sidebar')}catch(e){}
	</script>
	<!-- <div class="sidebar-shortcuts" id="sidebar-shortcuts">

		<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
			<a class="btn btn-success" href="<?php echo url('admin/News/news_list'); ?>" role="button" title="文章列表"><i class="ace-icon fa fa-signal"></i></a>
			<a class="btn btn-info" href="<?php echo url('admin/News/news_add'); ?>" role="button" title="添加文章"><i class="ace-icon fa fa-pencil"></i></a>
			<a class="btn btn-warning" href="<?php echo url('admin/Member/member_list'); ?>" role="button" title="学生列表"><i class="ace-icon fa fa-users"></i></a>
			<a class="btn btn-danger" href="<?php echo url('admin/Sys/sys'); ?>" role="button" title="站点设置"><i class="ace-icon fa fa-cogs"></i></a>
		</div>

		<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
			<a class="btn btn-success" href="<?php echo url('admin/News/news_list'); ?>" role="button" title="文章列表"></a>
			<a class="btn btn-info" href="<?php echo url('admin/News/news_add'); ?>" role="button" title="添加文章"></a>
			<a class="btn btn-warning" href="<?php echo url('admin/Member/member_list'); ?>" role="button" title="学生列表"></a>
			<a class="btn btn-danger" href="<?php echo url('admin/Sys/sys'); ?>" role="button" title="站点设置"></a>
		</div>
	</div> -->
	<!-- 菜单列表开始 -->
	<ul class="nav nav-list">
		<!--一级菜单遍历开始-->
		<?php if(is_array($menus) || $menus instanceof \think\Collection || $menus instanceof \think\Paginator): if( count($menus)==0 ) : echo "" ;else: foreach($menus as $key=>$v): if(!empty($v['_child'])): ?>
				<li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>open<?php endif; ?>">
			<a href="javascript:void(0);" class="dropdown-toggle">
				<i class="menu-icon fa <?php echo $v['css']; ?>"></i>
				<span class="menu-text"><?php echo $v['title']; ?></span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu">
				<!--二级菜单遍历开始-->
				<?php if(is_array($v['_child']) || $v['_child'] instanceof \think\Collection || $v['_child'] instanceof \think\Paginator): if( count($v['_child'])==0 ) : echo "" ;else: foreach($v['_child'] as $key=>$vv): if(!empty($vv['_child'])): ?>
						<li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active open<?php endif; ?>">
					<a href="javascript:void(0);" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo $vv['title']; ?>
						<b class="arrow fa fa-angle-down"></b>
					</a>
					<b class="arrow"></b>
					<ul class="submenu">
						<!--三级菜单遍历开始-->
						<?php if(is_array($vv['_child']) || $vv['_child'] instanceof \think\Collection || $vv['_child'] instanceof \think\Paginator): if( count($vv['_child'])==0 ) : echo "" ;else: foreach($vv['_child'] as $key=>$vvv): ?>
							<li class="<?php if((count($menus_curr) >= 3) AND ($menus_curr[2] == $vvv['id'])): ?>active<?php endif; ?>">
							<a href="<?php echo url($vvv['name']); ?>">
								<i class="menu-icon fa fa-caret-right"></i>
								<?php echo $vvv['title']; ?>
							</a>
							<b class="arrow"></b>
							</li>
						<?php endforeach; endif; else: echo "" ;endif; ?><!--三级菜单遍历结束-->
					</ul>
					</li>
					<?php else: ?>
					<li class="<?php if((count($menus_curr) >= 2) AND ($menus_curr[1] == $vv['id'])): ?>active<?php endif; ?>">
					<a href="<?php echo url($vv['name']); ?>">
						<i class="menu-icon fa fa-caret-right"></i>
						<?php echo $vv['title']; ?>
					</a>
					<b class="arrow"></b>
					</li>
					<?php endif; endforeach; endif; else: echo "" ;endif; ?><!--二级菜单遍历结束-->
			</ul>
			</li>
			<?php else: ?>
			<li class="<?php if((count($menus_curr) >= 1) AND ($menus_curr[0] == $v['id'])): ?>active<?php endif; ?>">
			<a href="<?php echo url($v['name']); ?>">
				<i class="menu-icon fa fa-caret-right"></i>
				<?php echo $v['title']; ?>
			</a>
			<b class="arrow"></b>
			</li>
			<?php endif; endforeach; endif; else: echo "" ;endif; ?><!--一级菜单遍历结束-->
	</ul><!-- 菜单列表结束 -->

	<!-- 菜单栏缩进开始 -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div><!-- 菜单栏缩进结束 -->
</div>

	<!-- 菜单栏结束 -->

	<!-- 主要内容开始 -->
	<div class="main-content">
		<div class="main-content-inner">
			<!-- 右侧主要内容页顶部标题栏开始 -->
			<div id="sidebar2" class="sidebar h-sidebar navbar-collapse collapse breadcrumbs-fixed printHide" data-sidebar="true" data-sidebar-scroll="true" data-sidebar-hover="true">
	<div class="nav-wrap-up pos-rel">
		<div class="nav-wrap">
			<ul class="nav nav-list">
				<?php if(($id_curr != '') AND (!empty($menus_child))): if(is_array($menus_child) || $menus_child instanceof \think\Collection || $menus_child instanceof \think\Paginator): if( count($menus_child)==0 ) : echo "" ;else: foreach($menus_child as $key=>$k): ?>
				<li>
					<a href="<?php echo url(''.$k['name'].''); ?>">
					<o class="font12 <?php if($id_curr == $k['id']): ?>rigbg<?php endif; ?>"><?php echo $k['title']; ?></o>
					</a>
					<b class="arrow"></b>
				</li>
				<?php endforeach; endif; else: echo "" ;endif; else: ?>
				<li>
					<a href="<?php echo url('admin/Index/index'); ?>">
						<o class="font12">欢迎使用三二分段招生管理系统</o>
					</a>
					<b class="arrow"></b>
				</li>
				<?php endif; ?>
			</ul>
		</div>
	</div><!-- /.nav-list -->
</div>

			<!-- 右侧主要内容页顶部标题栏结束 -->
			<!-- 右侧下主要内容开始 -->
			
<style>
.personal_table{background: #fff;margin: auto 10%;width: 656px;}

.personal_table table input{width: 100%;height: 100%;border:none;outline:none;text-align: center;color: #666;font-weight: bolder;}
.line-table-height{
    height:30px;
}
.line-table-height2{
    height: 90px;
}
.low_width_1{
    width:100px;
}
.low_width_2{
    width:110px;
}
.low_width_3{
    width:100px;
}
.low_width_4{
    width:110px;
}
.low_width_5{
    width:100px;
}
.low_width_6{
    width:100px;
}
.low_width_7{
    width:120px;
}
.title{
    text-align: center;
    font-weight: bold;
    font-size: 12px;
}
.content{
    text-align: center;
    font-family: 仿宋;
    font-size: 14px;
}
.content_area{
    text-align: left;
    font-family: 仿宋;
    font-size: 14px;
}

.k-s-content{
    border:1px solid #999;
    text-align: center;
}
.k-w-table {
    border-style:solid;
    border-color:rgb(148, 192, 210);
/*  border-color:#ccc; */
    border-width:1px;
    border-collapse:collapse;
}


</style>
<style type="text/css" media=print>
.printHide{display : none }
@page {
margin: 5px;
}
</style>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/shearphoto/css/ShearPhoto_f_zh-cn.css" />
	<div class="page-content ">
		<!--主题-->
		<div class="page-header printHide">
			<h1>
				您当前操作
				<small>
					<i class="ace-icon fa fa-angle-double-right"></i>
					修改中职学生信息
				</small>
			</h1>
		</div>
		<div class="row printHide">
			<div class="col-xs-12">
				<form class="form-horizontal memberform" name="member_list_edit" method="post" action="<?php echo url('admin/Member/member_runedit'); ?>">
					<input type="hidden" name="member_list_id" id="member_list_id" value="<?php echo $member_list_edit['member_list_id']; ?>" />

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 身份证号码：  </label>
						<div class="col-sm-10">
							<input type="text" name="member_list_username" id="member_list_username" value="<?php echo $member_list_edit['member_list_username']; ?>" placeholder="输入登录用户名" class="col-xs-10 col-sm-4" required/>
							<span class="lbl">&nbsp;&nbsp;<span class="red">*</span>必填</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 登入密码：  </label>
						<div class="col-sm-10">
							<input type="text" name="member_list_pwd" id="member_list_pwd" placeholder="输入登录密码" class="col-xs-10 col-sm-4" maxlength="8" minlength="6"/>
							<span class="lbl">&nbsp;&nbsp;<span class="red">*</span>必填，密码必须6位-8位</span>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="form-group">
						<label class="col-sm-2 control-label no-padding-right" for="form-field-1"> 姓名：  </label>
						<div class="col-sm-10">
							<input type="text" name="member_list_nickname" id="member_list_nickname" value="<?php echo $member_list_edit['member_list_nickname']; ?>"  placeholder="输入昵称" class="col-xs-10 col-sm-4" required/>
						</div>
					</div>
					<div class="space-4"></div>

					<div class="clearfix form-actions">
						<div class="col-md-offset-3 col-md-9">
							<button class="btn btn-info" type="submit">
								<i class="ace-icon fa fa-check bigger-110"></i>
								保存
							</button>

							&nbsp; &nbsp; &nbsp;
							<button class="btn" type="reset" onclick="window.history.go(-1);" onclick="window.history.go(-1);">
								<i class="ace-icon fa fa-undo bigger-110"></i>
								返回
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<?php if($member_list_edit['user_status']): 		$auth=new app\admin\model\AuthRule;
		$id_curr=$auth->get_url_id();
        if($auth->check_auth($id_curr))
		{
				}
		endif; ?>
        <!--startprint1-->
		<div class="row">
			<div class="col-xs-12">
			<div class="personal_table">
            <input type="hidden" name="member_list_id" value="<?php echo $member_list_edit['member_list_id']; ?>" />
            <table width="756px">
                <tbody><tr style="height:30px;">
                    <td colspan="2">

                    </td>
                    <td colspan="1" style="text-align: right;">
                        <span style="font-size: 13px;color: grey;">广东农工商职业技术学院</span>
                    </td>
                </tr>
                <tr><td colspan="3" style="text-align: center;font-size: 25px;font-weight: bold;border-top:1px solid #000000;line-height:48px;">2017年自主招生报名表</td></tr>
                <tr>
                    <td width="300px">考生号：<?php echo $info['GexamineeNumber']; ?></td>
                </tr>
            </tbody></table>
            <br>
            <table width="756px" class="k-w-table line_table">
                <tbody><tr class="line-table-height">
                    <td class="k-s-content low_width_1 title" style="text-align: center;">姓&nbsp;&nbsp;名</td>
                    <td class="k-s-content low_width_2 content"><input type="text" name="name" value="<?php echo $info['member_list_nickname']; ?>" disabled/></td>
                    <td class="k-s-content low_width_3 title">性&nbsp;&nbsp;别</td>
                    <td class="k-s-content low_width_4 content">
                        <input type="text" name="sex" value="<?php echo $info['sex']; ?>" valType="n" leng="18" disabled/>
                    </td>
                    <td class="k-s-content low_width_5 title">出生年月</td>
                    <td class="k-s-content low_width_6 content"><input type="text"  class="noBlur" name="date" value="<?php echo $info['date']; ?>" id="birth" disabled/></td>
                    <td class="k-s-content low_width_7" rowspan="5" style="text-align: center;position:relative">


                            <img  style="width: 125px; height: 175px; display: inline;" src="<?php if($member_list_edit['member_list_headpic']): ?><?php echo get_imgurl($member_list_edit['member_list_headpic'],1); else: ?><?php echo $yf_theme_path; ?>public/images/defaultGraph.jpg<?php endif; ?> " height="175px" width="125px"  class="headicon" data-toggle="modal" data-target="#myModal"  title="建议使用ie9以上浏览器，360浏览器建议使用极速模式">


                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title">民&nbsp;&nbsp;族</td>
                    <td class="k-s-content content"><input type="text" name="nation" value="<?php echo $info['nation']; ?>"/></td>
                    <td class="k-s-content low_width_3 title">籍&nbsp;&nbsp;贯=</td>
                    <td class="k-s-content content"><input type="text" name="nation" value="<?php echo $info['nation']; ?>"/></td>
                    <td class="k-s-content title">政治面貌</td>
                    <td class="k-s-content content"><input type="text" name="politicalOutlook" value="<?php echo $info['politicalOutlook']; ?>"/></td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title">身份证号</td>
                    <td class="k-s-content content" colspan="3"><input type="text" name="cardId" value="<?php echo $info['member_list_username']; ?>" disabled/></td>
                    <td class="k-s-content low_width_1 title">健康状态</td>
                    <td class="k-s-content content" colspan="1">
                        <input type="text" name="health" value="<?php echo $info['health']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title">外语语种</td>
                    <td class="k-s-content content" colspan="1"><input type="text" name="foreignLanguages" value="<?php echo $info['foreignLanguages']; ?>"/></td>
                    <td class="k-s-content low_width_1 title">考试类型</td>
                    <td class="k-s-content content" colspan="1">
                        <input type="text" name="testType" value="<?php echo $info['testType']; ?>"/>
                    </td>
                    <td class="k-s-content low_width_1 title">身高/体重</td>
                    <td class="k-s-content content" colspan="1">
                        <input type="text" name="figure" value="<?php echo $info['figure']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <!-- <td class="k-s-content title">生源地</td>
                   <td class="k-s-content content">
                       <input type="text" name="documentType" value="<?php echo $info['documentType']; ?>"/>
                   </td> -->
                    <td class="k-s-content  title">户口所在地</td>
                    <td class="k-s-content content" colspan="2"><input type="text" name="domicile" value="<?php echo $info['domicile']; ?>"/></td>
                    <td class="k-s-content  title" colspan="2">担任学生干部职位</td>
                    <td class="k-s-content content"><input type="text" name="leader" value="<?php echo $info['leader']; ?>"/></td>

                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title"><?php echo $school_type_name; ?>学校所在地</td>
                    <td class="k-s-content content" colspan="2">
                        <input type="text" name="school_place" value="<?php echo $info['school_place']; ?>"/>
                    </td>
                    <td class="k-s-content low_width_1 title">所在<?php echo $school_type_name; ?>学校名称</td>
                    <td class="k-s-content content" colspan="3">
                        <input type="text" name="school_name" value="<?php echo $info['school_name']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title"  colspan="2">联系人</td>
                    <td class="k-s-content content" colspan="2">
                        <input type="text" name="addressee" value="<?php echo $info['addressee']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">联系电话</td>
                    <td class="k-s-content content" colspan="2">
                        <input type="text" name="tell" value="<?php echo $info['tell']; ?>"/>
                    </td>
                </tr>
                <!-- 第6行 -->
                <tr class="line-table-height">
                    <td class="k-s-content low_width_1 title " colspan="2">接收邮寄通知书地址</td>
                    <td class="k-s-content content" colspan="3">
                        <input type="text" name="address" value="<?php echo $info['address']; ?>"/>
                    </td>
                    <td class="k-s-content title">邮编</td>
                    <td class="k-s-content content" colspan="1">
                        <input type="text" name="zipCode" value="<?php echo $info['zipCode']; ?>"/>
                    </td>
                </tr>

                <tr class="line-table-height">
                    <td class="k-s-content title" style=""  rowspan="4">家庭成员</td>
                    <td class="k-s-content title" colspan="1">关系</td>
                    <td class="k-s-content title" colspan="1">姓名</td>
                    <td class="k-s-content title" colspan="3">工作单位</td>
                    <td class="k-s-content title" colspan="2">电话</td>
                </tr>
                 <tr class="line-table-height">
                   <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyPunishment_0" value="<?php echo $info['family']['0']['familyPunishment']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyName_0" value="<?php echo $info['family']['0']['familyName']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="3">
                        <input type="text" name="familyWork_0" value="<?php echo $info['family']['0']['familyWork']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="2">
                        <input type="text" name="familyTell_0" value="<?php echo $info['family']['0']['familyTell']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyPunishment_1" value="<?php echo $info['family']['1']['familyPunishment']; ?>" />
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyName_1" value="<?php echo $info['family']['1']['familyName']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="3">
                        <input type="text" name="familyWork_1" value="<?php echo $info['family']['1']['familyWork']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="2">
                        <input type="text" name="familyTell_1" value="<?php echo $info['family']['1']['familyTell']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyPunishment_2" value="<?php echo $info['family']['2']['familyPunishment']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="familyName_2" value="<?php echo $info['family']['2']['familyName']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="3">
                        <input type="text" name="familyWork_2" value="<?php echo $info['family']['2']['familyWork']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="2">
                        <input type="text" name="familyTell_2" value="<?php echo $info['family']['2']['familyTell']; ?>"/>
                    </td>
                </tr>

                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">获奖情况</td>
                    <td class="k-s-content title" colspan="6">
                        <input type="text" name="awards" value="<?php echo $info['awards']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">特长生</td>
                    <td class="k-s-content title" colspan="6">
                        <input type="text" name="excellentStudent" value="<?php echo $info['excellentStudent']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">专业志愿</td>
                    <td class="k-s-content title" colspan="6">
                        <input type="text" name="wish" value="<?php echo $info['wish']; ?>"/>
                    </td>
                </tr>
                <?php if($info['school_type'] == 'vocational'): ?>
                <tr class="line-table-height">
                    <td class="k-s-content title low_width_1" style=""  rowspan="2"><?php echo $school_type_name; ?>阶段获奖技能证书和奖项(改项目内容须学校学籍部门审核并盖章确认，涂改作废)</td>
                    <td class="k-s-content title" colspan="2">所学的专业</td>
                    <td class="k-s-content title" colspan="1"><input type="text" name="major" /></td>
                    <td class="k-s-content title" colspan="2">技能证书名称及等级</td>
                    <td class="k-s-content title" colspan="1"><input type="text" name="skillCertificate"  value="<?php echo $info['skillCertificate']; ?>"/></td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="2">技能比赛名称及等级</td>
                    <td class="k-s-content title" colspan="3">
                        <input type="text" name="skill" value="<?php echo $info['skillCompetition']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        （学校学籍部门盖章）
                    </td>
                </tr>
                <?php else: ?>
                <tr class="line-table-height">
                    <td class="k-s-content title" style=""  rowspan="4"><?php echo $school_type_name; ?>阶段学习成绩</td>
                    <td class="k-s-content title" colspan="1">文科类艺术类</td>
                    <td class="k-s-content title" colspan="1">学业水平测试成绩</td>
                    <td class="k-s-content title" colspan="1">理科类体育类</td>
                    <td class="k-s-content title" colspan="1">学业水平测试成绩</td>
                    <td class="k-s-content title" colspan="1">语数外</td>
                    <td class="k-s-content title" colspan="1">学业水平测试成绩</td>
                </tr>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">
                        化学
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watChemistry" value="<?php echo $info['watGrade']['watChemistry']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        政治
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watPolitics" value="<?php echo $info['watGrade']['watPolitics']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        语文
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watChinese" value="<?php echo $info['watGrade']['watChinese']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                   <td class="k-s-content title" colspan="1">
                        物理
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watPhysics" value="<?php echo $info['watGrade']['watPhysics']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        历史
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watHistory" value="<?php echo $info['watGrade']['watHistory']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        数学
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watMatch" value="<?php echo $info['watGrade']['watMatch']; ?>"/>
                    </td>
                </tr>
                <tr class="line-table-height">
                   <td class="k-s-content title" colspan="1">
                        生物
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watBiology" value="<?php echo $info['watGrade']['watBiology']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        地理
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watGeography" value="<?php echo $info['watGrade']['watGeography']; ?>"/>
                    </td>
                    <td class="k-s-content title" colspan="1">
                        英语
                    </td>
                    <td class="k-s-content title" colspan="1">
                        <input type="text" name="watEnglish" value="<?php echo $info['watGrade']['watEnglish']; ?>"/>
                    </td>
                </tr>
                <?php endif; ?>
                <tr class="line-table-height">
                    <td class="k-s-content title" colspan="1">个人承诺</td>
                    <td class="k-s-content title promise" colspan="6">
                        <p class="prp1">我已详细阅读了自主招生报考条件、报名须知及注意事项，符合报考条件。本人保证填报资料和寄送材料真实准确，如因个人填报信息失实或不符合报考条件而被取消录考资格的，由本人负责。</p>
                        <p class="prp2"><span class="">考生亲笔签名：</span><span>日期：</span></p>
                    </td>
                </tr>

                <tr class="line-table-height2">
                    <td class="k-s-content title">高校资格审查意见</td>
                    <td class="k-s-content content_area" colspan="3">
                       （盖章）
                    </td>
                    <td class="k-s-content title">考生所在学校意见</td>
                    <td class="k-s-content content_area" colspan="2">
                        （盖章）
                    </td>
                </tr>
            </tbody></table>
			</div>
			</div>

		</div>
        <!--endtprint1-->
        <div class="clearfix form-actions printHide">
            <div class="col-md-offset-3 col-md-9"  href="<?php echo url('admin/Member/member_active'); ?>" data-id="<?php echo $member_list_edit['member_list_id']; ?>">
                <?php if($member_list_edit['user_status'] == 1): ?>
                <button class="btn btn-info btn-info member_status_btn" type="button" disabled="">
                    已审核通过,不可更改
                </button>

                <?php else: ?>
                <button class="btn btn-info btn-primary member_status_btn" type="button">
                    审核通过
                </button>
                <?php endif; ?>
                <!-- <button class="btn btn-info btn-primary printHide" onclick="preview(1)">打印</button> -->

            </div>
        </div>
	</div><!-- /.page-content -->

    <?php if($member_list_edit['user_status'] ==1): ?>
    <script type="text/javascript">
     $('.personal_table').find('input').attr('disabled',true);
    </script>
    <?php endif; ?>
    <script  type="text/javascript" src="__PUBLIC__/shearphoto/js/ShearPhoto.js" ></script>
<script  type="text/javascript"  src="__PUBLIC__/shearphoto/js/alloyimage.js" ></script>
<script  type="text/javascript"  src="__PUBLIC__/shearphoto/js/handle_f.js" ></script>
<script type="text/javascript">
    var SHEAR = {
        PATH_RES: '__PUBLIC__',
        PATH_AVATAR: '__ROOT__/data/upload/avatar',
        URL:"<?php echo url('admin/Member/avatar',['member_list_id' => $member_list_edit['member_list_id']]); ?>",
    };
</script>

    <!-- 显示模态框（Modal） -->
        <div class="modal fade modal-avatar printHide" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display:none;">
            <div class="modal-dialog" style="width:60%;">
                <div class="modal-content"  style="min-width:620px;">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel">
                            选择头像
                        </h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                    <div id="shearphoto_loading"><?php echo lang('program loading'); ?>......</div>
                                    <div id="shearphoto_main">

                                        <div class="primary">
                                            <div id="main">
                                                <div class="point"></div>
                                                <div id="SelectBox">
                                                    <form    id="ShearPhotoForm" enctype="multipart/form-data" method="post"  target="POSTiframe">
                                                        <a href="javascript:;" id="selectImage"><input type="file"  name="UpFile" autocomplete="off"/></a>
                                                    </form>

                                                </div>
                                                <div id="relat">
                                                    <div id="black"></div>
                                                    <div id="movebox">
                                                        <div id="smallbox">
                                                            <img src="__PUBLIC__/shearphoto/images/default.gif" class="MoveImg"  style="max-width:300%"/>
                                                        </div>
                                                        <i id="borderTop"></i>
                                                        <i id="borderLeft"></i>
                                                        <i id="borderRight"></i>
                                                        <i id="borderBottom"></i>
                                                        <i id="BottomRight"></i>
                                                        <i id="TopRight"></i>
                                                        <i id="Bottomleft"></i>
                                                        <i id="Topleft"></i>
                                                        <i id="Topmiddle"></i>
                                                        <i id="leftmiddle"></i>
                                                        <i id="Rightmiddle"></i>
                                                        <i id="Bottommiddle"></i>
                                                    </div>
                                                    <img src="__PUBLIC__/shearphoto/images/default.gif" class="BigImg" />
                                                </div>
                                            </div>
                                            <div style="clear: both"></div>
                                            <div id="Shearbar">
                                                <a id="LeftRotate" href="javascript:;"><em></em>向左转</a>
                                                <em class="hint L"></em>
                                                <div class="ZoomDist" id="ZoomDist">
                                                    <div id="Zoomcentre"></div>
                                                    <div id="ZoomBar"></div>
                                                    <span class="progress"></span>
                                                </div>
                                                <em class="hint R"></em>
                                                <a id="RightRotate" href="javascript:;">向右转<em></em></a>
                                                <p class="Psava">
                                                    <a id="againIMG"  href="javascript:;">重新选择</a>
                                                    <a id="saveShear" href="javascript:;">保存</a>
                                                </p>
                                            </div>
                                        </div>
                                        <div style="clear: both"></div>
                                    </div>
                                    <div id="photoalbum">
                                        <i id="close"></i>
                                        <ul>
                                            <li><img src="__PUBLIC__/shearphoto/file/photo/1.jpg" serveUrl="file/photo/1.jpg" /></li>
                                            <li><img src="__PUBLIC__/shearphoto/file/photo/2.jpg" serveUrl="file/photo/2.jpg" /></li>
                                            <li><img src="__PUBLIC__/shearphoto/file/photo/3.jpg" serveUrl="file/photo/3.jpg" /></li>
                                            <li><img src="__PUBLIC__/shearphoto/file/photo/4.jpg" serveUrl="file/photo/4.jpg" /></li>
                                    </div>
                            </div>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
<script type="text/javascript">
function preview(oper)
{
if (oper < 10)
{
bdhtml=window.document.body.innerHTML;//获取当前页的html代码
sprnstr="<!--startprint"+oper+"-->";//设置打印开始区域
eprnstr="<!--endprint"+oper+"-->";//设置打印结束区域
prnhtml=bdhtml.substring(bdhtml.indexOf(sprnstr)+18); //从开始代码向后取html
prnhtmlprnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html
window.document.body.innerHTML=prnhtml;
window.print();
window.document.body.innerHTML=bdhtml;
} else {
window.print();
}
}
</script>

			<!-- 右侧下主要内容结束 -->
		</div>
	</div><!-- 主要内容结束 -->
	<!-- 页脚开始 -->
	<div class="footer printHide">
	<div class="footer-inner">
		<div class="footer-content">
			<span class="bigger-120">
				<!-- <span class="blue bolder">三二分段</span> -->
				三二分段招生管理系统 &copy; 2015-<?php echo date('Y'); ?>
			</span>
		</div>
	</div>
</div>

	<!-- 页脚结束 -->
	<!-- 返回顶端开始 -->
	<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
		<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
	</a>
	<!-- 返回顶端结束 -->
</div><!-- 整个页面内结束 -->

<!-- ace的js,可以通过打包生成,避免引入文件数多 -->
<script src="__PUBLIC__/ace/js/ace.js"></script>
<script src="__PUBLIC__/ace/js/ace.min.js"></script>
<!-- jquery.form、layer、yfcmf的js -->
<script src="__PUBLIC__/others/jquery.form.js"></script>
<script src="__PUBLIC__/others/maxlength.js"></script>
<script src="__PUBLIC__/layer/layer_zh-cn.js"></script>
<script src="__PUBLIC__/datePicker/bootstrap-datepicker.js"></script>
<script src="__PUBLIC__/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="__PUBLIC__/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js"></script>
<script src="__PUBLIC__/yfcmf/yfcmf.js?<?php echo time(); ?>"></script>
<!-- 此页相关插件js -->

	<script type="text/javascript" src="__PUBLIC__/others/region.js"></script>

<!-- 与此页相关的js -->
</body>
</html>
