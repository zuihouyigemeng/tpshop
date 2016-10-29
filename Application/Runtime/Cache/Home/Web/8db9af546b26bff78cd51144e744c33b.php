<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title>用户登录 - <?php echo ($web_title); ?></title>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <meta name="keywords" content="<?php echo ($web_keywords); ?>" />
    <link href="<?php echo ($web_tplpath); ?>css/login.css" rel="stylesheet">

    <!--[if lt IE 8]>
	<style type="text/css">
		.searchs {float:left;width:620px}
		.searchs>form {float:left;width:608px;height:35px;display:block}
		.searchs>.hot-search {float:left;display:block;width:608px}
	</style>
	<![endif]-->
  </head>
  <body>
    
  	<div class="top">
  		<div class="cent">
        <div class="logo"><a href="<?php echo U('index/index');?>" title="<?php echo ($web_title); ?>"><img src="<?php echo ($web_logo); ?>" /></a></div>
  			<div class="back-page">
  				<a href="<?php echo U('index/index');?>">返回首页</a>
  			</div>
  		</div>
  	</div>
  	<div class="login-back" style="background:#F5AD00;">
    <form method="post" action="<?php echo U();?>" class="form-user">
		  <div class="login-background" style="background: url(<?php echo ($web_tplpath); ?>images/login1.jpg) no-repeat left center;">
        <div class="login-block">
          <h5>登录<small><a href="<?php echo U('public/reg');?>" class="a1">免费注册</a></small></h5>
          <div class="input-ls">
            <input type="text" name="username" placeholder="请使用注册邮箱或手机号" />
            <span class="input-user icon icon-mail-alt"></span>
          </div>
          <div class="input-ls">
            <input type="password" name="password"/>
            <span class="input-user icon icon-lock"></span>
          </div>
          <div class="login-text"><a href="<?php echo U('public/forgetpwd');?>">忘记密码？</a></div>
          <div class="login-info">请输入帐号</div>
          <div class="input-btn"><button class="btn btn-big btn-yellow ajax-post" type="submit" target-form="form-user">登 陆</button></div>
          <div class="other-login">
            其他登录方式：
            <?php if(!empty($login_qq_appid)): ?><a href="<?php echo U('public/qq_login');?>" class="shareicon shareicon2"><i class="icon icon-qq"></i></a><?php endif; ?>
            <?php if(!empty($login_wx_appid)): ?><a href="javascript:;" class="shareicon shareicon3"><i class="icon icon-wechat"></i></a><?php endif; ?>
            <!-- <a class="shareicon shareicon1"><i class="icon icon-weibo"></i></a> -->
          </div>
        </div>

        <div class="login-block qecode-login">
          <div class="qecode" id="login_container">
           
          </div>
          <div class="back-home">
            <img src="<?php echo ($web_tplpath); ?>images/return_03.gif">
          </div>
        </div>

		  </div>
    </form>
  	</div>
    <div class="footer">Copyright &copy; 2015 <?php echo ($web_title); ?> <?php echo ($web_url); ?> 版权所有 <?php echo ($web_icp); ?></div>
	

	<!--[if lt IE 9]>
	<script src="<?php echo ($web_tplpath); ?>js/jquery.pseudo.js"></script>
	<![endif]-->

  <script src="<?php echo ($web_tplpath); ?>js/jquery.min.js"></script>
  <script type="text/javascript">var tplpath='<?php echo ($web_tplpath); ?>'</script>
  <script src="/shop/Public/Static/layer/layer.js"></script>
  <script src="<?php echo ($web_tplpath); ?>js/ajax.js"></script>
  <script src="<?php echo ($web_tplpath); ?>js/public.js"></script>
  <?php if(!empty($login_wx_appid)): ?><script src="http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
    <script type="text/javascript">
      var obj = new WxLogin({
        id:"login_container",
        appid: "<?php echo C('LOGIN_WX_APPID');?>",
        scope: "snsapi_login",
        redirect_uri: "<?php echo urlencode(C("WEB_URL").'/public/weixinlogin');?>",
        state: "<?php echo ($wid); ?>",
        style: "",
        href: ""
      });
    </script><?php endif; ?>
  </body>
</html>