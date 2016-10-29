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
	<h2>栏目</h2>
</div>
<div class="cl-mcont">
	<div class="row">
		<div class="col-md-12">
			<div class="block-flat">
				<div class="header">							
					<h3 class="hthin"><?php echo ($meta_title); ?></h3>
				</div>
				<div class="content">
					<div class="col-sm-12">
						<div class="pull-right">
							<button type="button" class="btn btn-success btn-rad md-trigger" url="<?php echo U('Category/add');?>" data-modal="form-primary"><i class="fa fa-plus-square"></i> 添加主栏目</button>
						</div>
					</div>
					<div class="category">
						<div class="row color-primary">
							<div class="col-sm-2 col-md-4 col-lg-1">ID</div>
							<div class="col-sm-2 col-md-4 col-lg-1">折叠</div>
							<div class="col-sm-6 col-md-6 col-lg-8">栏目名</div>
							<div class="col-sm-4 col-md-4 col-lg-2 text-right">操作</div>
						</div>
						<?php echo R('Category/tree', array($tree));?> 
					</div>
				</div>
				<div class="md-overlay"></div>
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

<script type="text/javascript">
	$(document).ready(function(){		
		$(".md-trigger").click(function(){
			 if (target = $(this).attr('url')) {
				layer.open({
					type: 2,
					title: false,
					closeBtn: false,
					area: ['600px', '520px'],
					fix: false,
					skin: 'layui-layer-nobg',
					content: [target,'no']
				});
			}
		})
		$(".md-triggers").click(function(){
			 if (target = $(this).attr('url')) {
				layer.open({
					type: 2,
					title: false,
					closeBtn: false,
					area: ['600px', '360px'],
					fix: false,
					skin: 'layui-layer-nobg',
					content: [target,'no']
				});
			}
		})
		$(".category dd").prev().find(".fold i").addClass("fa-minus-square-o").click(function(){
			var self = $(this);
			if(self.hasClass("fa-minus-square-o")){
				self.closest("dt").next().slideUp("fast", function(){
					self.removeClass("fa-minus-square-o").addClass("fa-plus-square-o");
				});
			} else {
				self.closest("dt").next().slideDown("fast", function(){
					self.removeClass("fa-plus-square-o").addClass("fa-minus-square-o");
				});
			}
		});
		/* 三级分类删除新增按钮 */
		$(".category dd .add-sub").remove();
		
		highlight_subnav('<?php echo U('Category/index');?>');
	});
</script>

</body>
</html>