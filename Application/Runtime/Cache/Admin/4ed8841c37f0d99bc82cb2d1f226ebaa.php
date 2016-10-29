<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="/shop/Public/Admin/images/favicon.png">
	<title>跳转提示</title>
	<!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="/shop/Public/Admin/js/bootstrap/dist/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/shop/Public/Admin/fonts/font-awesome-4/css/font-awesome.min.css" />
	<!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="/shop/Public/Admin/css/style.css" />
</head>
<body>
<div class="sign-up-container">
    <div class="middle-sign-up">
        <div class="modal fade in" style="display: block;">
        	<div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center">
                    	<?php if(isset($message)) {?>
                        <div class="i-circle success"><i class="fa fa-check"></i></div>
                        <h4><?php echo($message); ?></h4>
                        <?php }else{?>
                        <div class="i-circle warning"><i class="fa fa-warning"></i></div>
                        <h4><?php echo($error); ?></h4>
                        <?php }?>
                        <p>页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></p>
                    </div>
                </div>
            </div>
            </div>
        </div>
	</div> 
</div>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/behaviour/web.js"></script>
<script type="text/javascript" src="/shop/Public/Admin/js/jquery.ui/jquery-ui.js"></script>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>