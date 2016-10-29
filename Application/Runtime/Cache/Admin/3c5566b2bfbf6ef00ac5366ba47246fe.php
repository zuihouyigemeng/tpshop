<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="/shop/Public/Admin/images/favicon.png">
	<title><?php echo ($webtitle); ?>--用户登陆</title>
	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/shop/Public/Admin/fonts/font-awesome-4/css/font-awesome.min.css" />
	<!-- Custom styles for this template -->
	<link rel="stylesheet" type="text/css" href="/shop/Public/Admin/css/style.css" />	
</head>
<body class="texture">
<div id="cl-wrapper" class="login-container">
	<div class="middle-login">
		<div class="block-flat">
			<div class="header">							
				<h3 class="text-center"><img class="logo-img" src="/shop/Public/Admin/images/logo.png" alt="logo"/> <?php echo ($webtitle); ?></h3>
			</div>
			<div>
				<form style="margin-bottom: 0px !important;" class="form-horizontal" method="post" action="<?php echo U();?>">
					<div class="content">
						<h4 class="title">登 陆</h4>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-user"></i></span>
										<input type="text" placeholder="用户名" name="username" class="form-control">
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-12">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										<input type="password" placeholder="密码" name="password" class="form-control">
									</div>
								</div>
							</div>
							
					</div>
					<div class="foot">
						<button class="btn btn-primary" data-dismiss="modal" type="submit">登 陆</button>
					</div>
				</form>
			</div>
		</div>
		<div class="text-center out-links"><a href="#"><?php echo ($webtitle); ?> &copy; 2014</a></div>
	</div> 
</div>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/behaviour/web.js"></script>
</body>
</html>