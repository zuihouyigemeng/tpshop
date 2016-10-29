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
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/jquery.icheck/skins/square/blue.css" />

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
	<h2>商城</h2>
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
									<label class="col-sm-2 control-label">名称</label>
									<div class="col-sm-6">
										<input type="text" name="name" class="form-control" value="<?php echo ((isset($info["name"]) && ($info["name"] !== ""))?($info["name"]):''); ?>" placeholder="名称"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">分类</label>
									<div class="col-sm-6">
										<select class="form-control" name="category">
				                        	<?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($info['category']) == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["html"]); echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				                         </select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">专区</label>
									<div class="col-sm-6">
										<select class="form-control" name="ten">
											<option value="0" <?php if(($info['ten']) == "0"): ?>selected="selected"<?php endif; ?>>普通区</option>
				                        	<?php if(is_array($ten)): $i = 0; $__LIST__ = $ten;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($info['ten']) == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["html"]); echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				                         </select>
									</div>
								</div>
								<?php if(isset($info)): ?><div class="form-group">
									<label class="col-sm-2 control-label">价格</label>
									<div class="col-sm-6">
										<input type="text" name="edit_price" class="form-control" value="<?php echo ((isset($info["edit_price"]) && ($info["edit_price"] !== ""))?($info["edit_price"]):''); ?>" placeholder="修改价格"/>
									</div>
								</div>
								<?php else: ?>
								<div class="form-group">
									<label class="col-sm-2 control-label">价格</label>
									<div class="col-sm-6">
										<input type="text" name="price" class="form-control" value="<?php echo ((isset($info["price"]) && ($info["price"] !== ""))?($info["price"]):''); ?>" placeholder="价格"/>
									</div>
								</div><?php endif; ?>
								<div class="form-group">
									<label class="col-sm-2 control-label">图片</label>
									<div class="col-sm-6">
										<input type="file" id="upload_picture">
										<input type="hidden" name="cover_id" id="cover_id" value="<?php echo ((isset($info["cover_id"]) && ($info["cover_id"] !== ""))?($info["cover_id"]):0); ?>"/>
										<div id="shop-pic">
										<?php if(!empty($info["picurl"])): ?><img src="<?php echo ($info["picurl"]); ?>" class="img-thumbnail" style="height:100px;"/><?php endif; ?>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">详情</label>
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
									<label class="col-sm-2 control-label">推荐</label>
									<div class="col-sm-6">
										<?php if(is_array(C("DOCUMENT_POSITION"))): $i = 0; $__LIST__ = C("DOCUMENT_POSITION");if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pos): $mod = ($i % 2 );++$i;?><label class="checkbox-inline"><input class="icheck" type="checkbox" value="<?php echo ($key); ?>" name="position[]" <?php if(check_document_position($info['position'],$key)): ?>checked="checked"<?php endif; ?>> <?php echo ($pos); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">购买价格</label>
									<div class="col-sm-6">
										<input type="text" name="buy_price" class="form-control" value="<?php echo ((isset($info["buy_price"]) && ($info["buy_price"] !== ""))?($info["buy_price"]):''); ?>" placeholder="购买价格"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">购买链接</label>
									<div class="col-sm-6">
										<input name="buy_url" type="text" class="form-control" value="<?php echo ((isset($info["buy_url"]) && ($info["buy_url"] !== ""))?($info["buy_url"]):''); ?>" placeholder="购买链接">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">网页标题</label>
									<div class="col-sm-6">
										<input type="text" name="meta_title" class="form-control" value="<?php echo ((isset($info["meta_title"]) && ($info["meta_title"] !== ""))?($info["meta_title"]):''); ?>" placeholder="SEO 网页标题"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">关键字</label>
									<div class="col-sm-6">
										<input name="keywords" type="text" class="form-control" value="<?php echo ((isset($info["keywords"]) && ($info["keywords"] !== ""))?($info["keywords"]):''); ?>" placeholder="SEO 关键字">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">描述</label>
									<div class="col-sm-6">
										<input name="description" type="text" class="form-control" value="<?php echo ((isset($info["description"]) && ($info["description"] !== ""))?($info["description"]):''); ?>" placeholder="SEO 描述">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-2 control-label">人气</label>
									<div class="col-sm-6">
										<input type="text" name="hits" class="form-control" value="<?php echo ((isset($info["hits"]) && ($info["hits"] !== ""))?($info["hits"]):0); ?>"/>
									</div>
								</div>
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

	<script type="text/javascript" src="/shop/Public/Admin/js/behaviour/weixin.js"></script>
	<script type="text/javascript" src="/shop/Public/Admin/js/jquery.icheck/icheck.min.js"></script>
	<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap.datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>
	<script type="text/javascript" src="/shop/Public/Static/uploadify/jquery.uploadify.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".datetime").datetimepicker({autoclose: true,language: 'zh-CN'});
			$('.icheck').iCheck({
			   checkboxClass: 'icheckbox_square-blue checkbox',
			   radioClass: 'iradio_square-blue'
			});
			setValue('display','<?php echo ($info["display"]); ?>');
			highlight_subnav('<?php echo U('Shop/add');?>');
			
			$("#upload_picture").uploadify({
				"height"          : 35,
				"swf"             : "/shop/Public/Static/uploadify/uploadify.swf",
				"fileObjName"     : "download",
				"buttonClass"     :  "btn btn-success fa fa-upload no-padding",
				"buttonText"      : " 上传图片",
				"uploader"        : "<?php echo U('File/uploadPicture',array('session_id'=>session_id()));?>",
				"width"           : 100,
				'removeTimeout'	  : 1,
				'fileTypeExts'	  : '*.jpg; *.png; *.gif;',
				"onUploadSuccess" : uploadPicture,
				'onFallback' : function() {
					alert('未检测到兼容版本的Flash.');
				}
			});
			function uploadPicture(file, data){
				var data = $.parseJSON(data);
				var src = '';
				if(data.status){
					$("#cover_id").val(data.id);
					src = data.url || '/shop' + data.path
					$("#shop-pic").html(
						'<img src="/shop' + src + '" class="img-thumbnail" style="height:100px;"/>'
					);
				} else {
					layer.msg(data.info, {icon: 2});
				}
			}
		});
	</script>

</body>
</html>