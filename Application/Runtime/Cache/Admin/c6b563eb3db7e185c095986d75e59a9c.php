<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="<?php echo C('WEB_SITE_KEYWORD');?>" name="keywords">
	<meta name="description" content="<?php echo C('WEB_SITE_DESCRIPTION');?>">
	<link rel="shortcut icon" href="/shop/Public/Admin/images/favicon.png">
	<title><?php echo C('WEB_SITE_TITLE');?>--用户中</title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/bootstrap/dist/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/fonts/font-awesome-4/css/font-awesome.min.css" />
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv-printshiv.js"></script>
		<![endif]-->
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" type="text/css" href="shop//shop/Public/Admin/js/jquery.nanoscroller/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/jquery.select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/bootstrap.slider/css/slider.css" />
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/css/pygments.css" />
	<!-- Custom styles for this template -->
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/css/style.css" />
	
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/bootstrap.datetimepicker/css/bootstrap-datetimepicker.min.css" />

</head>
<body>
<!-- Fixed navbar -->
<div id="head-nav" class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="fa fa-gear"></span> </button>
			<a class="navbar-brand" href="#"><span>综合管理后台</span></a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			</ul>
			<ul class="nav navbar-nav navbar-right user-nav">
				<li class="dropdown profile_menu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user fa-2x"></i> <span><?php echo session('user_auth.username');?></span> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="<?php echo U('Member/password');?>">修改密码</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo U('public/logout');?>">退出</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
<div id="cl-wrapper" class="fixed-menu">
	<div class="cl-sidebar">
		<div class="cl-toggle"><i class="fa fa-bars"></i></div>
		<div class="cl-navblock">
			<div class="menu-space">
				<div class="content">
					<!-- 子导航 -->
					
						<ul class="cl-vnavigation">
						<?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i; if(!empty($sub_menu)): if((count($sub_menu)) > "1"): ?><li><a href="#"><i class="<?php echo ($sub_menu[0]['icon']); ?>"></i><span><?php echo ($key); ?></span></a>
										<ul class="sub-menu"><?php endif; ?>
								<?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li><a href="<?php echo (u($menu["url"])); ?>"><?php if((count($sub_menu)) == "1"): ?><i class="<?php echo ($menu["icon"]); ?>"></i><span><?php endif; echo ($menu["title"]); if((count($sub_menu)) == "1"): ?></span><?php endif; ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
								<?php if((count($sub_menu)) > "1"): ?></ul>
									</li><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					
					<!-- /子导航 -->
				</div>
			</div>
			<div class="text-right collapse-button" style="padding:7px 9px;">
				<button id="sidebar-collapse" class="btn btn-default" style=""><i style="color:#fff;" class="fa fa-angle-left"></i></button>
			</div>
		</div>
	</div>
	
<div>
<div class="page-head">
	<h2>文章</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">							
					<h3 class="hthin"><?php echo ($meta_title); ?></h3>
				</div>
				<div class="content">
					<div class="tab-container">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#basics" data-toggle="tab">基础</a></li>
							<li><a href="#senior" data-toggle="tab">高级</a></li>
						</ul>
						<form action="<?php echo U();?>" class="form-horizontal"  method="post">
						<div class="tab-content">
							<div class="tab-pane active" id="basics">
								<div class="form-group">
									<label class="col-sm-2 control-label">标题</label>
									<div class="col-sm-6">
										<input type="text" name="title" class="form-control" value="<?php echo ((isset($info["title"]) && ($info["title"] !== ""))?($info["title"]):''); ?>" placeholder="标题"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">栏目</label>
									<div class="col-sm-6">
										<select class="form-control" name="category">
											<option value="">选择栏目</option>
											<option value="1">公告</option>
											<option value="2">帮助</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">内容</label>
									<div class="col-sm-10">
										<script type="text/javascript" src="/shop/Public/Static/ueditor/ueditor.config.js"></script>
										<script type="text/javascript" src="/shop/Public/Static/ueditor/ueditor.all.js"></script>
										<script id="container" name="content" style="width:100%;height:500px;" type="text/plain"><?php echo (stripslashes(htmlspecialchars_decode($info["content"]))); ?></script>
										<script type="text/javascript">
											var ue = UE.getEditor('container',{
												serverUrl :'<?php echo U('Ueditor/Index');?>',
           										UEDITOR_HOME_URL:'/shop/Public/Static/ueditor/',
											});
										</script>
									</div>
								</div>
							</div>
							<div class="tab-pane" id="senior">
								<div class="form-group">
									<label class="col-sm-2 control-label">创建时间</label>
									<div class="col-sm-6">
									  <div class="input-group date datetime" data-min-view="2" data-date-format="yyyy-mm-dd">
										<input type="text" name="create_time" class="form-control" value="<?php echo (time_format($info["create_time"],'Y-m-d')); ?>"/>
										<span class="input-group-addon btn btn-primary"><span class="glyphicon glyphicon-th"></span></span>
									  </div>					
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">可见性</label>
									<div class="col-sm-6">
										<select class="form-control" name="display">
											<option value="1">可见</option>
											<option value="0">不可见</option>
										</select>
									</div>
								</div>
							</div>
							<input type="hidden" name="id" value="<?php echo ((isset($info["id"]) && ($info["id"] !== ""))?($info["id"]):''); ?>">
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button class="btn btn-primary ajax-post" type="submit" target-form="form-horizontal">提 交</button>
								</div>
						  	</div>
						</div>
						</form>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

</div>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.ui/jquery-ui.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.nanoscroller/jquery.nanoscroller.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.switch/bootstrap-switch.min.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.select2/select2.min.js"></script>
<script type="text/javascript" src="/shop/Public/Static/layer/layer.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/behaviour/web.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap/dist/js/bootstrap.min.js"></script>

	<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
	<script type="text/javascript" src="/shop/Public/Static/uploadify/jquery.uploadify.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			 $(".datetime").datetimepicker({autoclose: true});
		
			<?php if(isset($info["id"])): ?>highlight_subnav('<?php echo U('News/index');?>');
				setValue("category", <?php echo ((isset($info["category"]) && ($info["category"] !== ""))?($info["category"]):1); ?>);
			<?php else: ?>
				highlight_subnav('<?php echo U('News/add');?>');<?php endif; ?>	
		});
	</script>

</body>
</html>