<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <title><?php echo ($web_title); ?></title>
    <meta name="description" content="<?php echo ($web_description); ?>" />
    <meta name="keywords" content="<?php echo ($web_keywords); ?>" />
    <link href="<?php echo ($web_tplpath); ?>css/oenpay.css" rel="stylesheet">
    <!--[if lt IE 8]>
	<style type="text/css">
		.searchs {float:left;width:620px}
		.searchs>form {float:left;width:608px;height:35px;display:block}
		.searchs>.hot-search {float:left;display:block;width:608px}
	</style>
	<![endif]-->
  </head>
  <body> 
  	<!--[if lt IE 9]>
  	<div class="chrome">您的浏览器版本太低啦~请升级您的浏览器。本站推荐<a href="http://liulanqi.baidu.com/" class="a1" target="_blank">百度浏览器</a> <a href="http://liulanqi.baidu.com/" class="a1" target="_blank">点击下载</a></div>
<![endif]-->
<div class="top-line">
	<div class="g-wrap">
		<div class="tl-left">欢迎来到<?php echo ($web_title); ?>！</div>
		<div class="tl-right">
			<?php if(isset($_SESSION['hx_users']['user_auth'])): ?><a href="<?php echo U('user/index');?>"><?php echo ($username); ?></a> 
			<a href="<?php echo U('user/index');?>">我的夺宝</a>
			<a href="<?php echo U('public/logout');?>">[ 退出 ]</a>
			<?php else: ?>
			<a href="<?php echo U('public/login');?>">请登录</a> 
			<a href="<?php echo U('public/reg');?>">免费注册</a><?php endif; ?>
		</div>
	</div>
</div>
<div class="top-back">
	<!-- LOGO 开始 -->
  	<div class="container">
		<div class="logos">
			<div class="logo"><img src="<?php echo ($web_logo); ?>" /></div>
			<div class="top-people"></div>
		</div>
  	</div>
	<!-- LOGO 结束 -->
	<!-- 导航开始 -->
	<div class="navbar category">
		<div class="container sNav">
			<div class="navbar-all-class class-hidden">
  				<a href="#">全部商品分类</a>
  				<div class="left-class left-cl-hidden">
	  				<a href="<?php echo U('list/index/');?>"><span class="icon icon-star-empty"></span>全部商品</a>
	  				<?php $_result=R('list/type');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('list/index/?id='.$vo['id']);?>"><span class="<?php echo ($vo['icon']); ?>"></span><?php echo ($vo['title']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
  			</div>
			<div class="navbar-class"><a href="<?php echo U('index/index');?>">首页</a></div>
			<div class="navbar-class"><a href="<?php echo U('user/announced');?>">最新揭晓</a></div>
			<?php $_result=R('ten/ten');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="navbar-class"><a href="<?php echo U('ten/index',array('ten'=>$vo['id']));?>"><?php echo ($vo['title']); ?></a></div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="navbar-class"><a href="<?php echo U('user/displays');?>">晒单分享</a></div>
			<div class="navbar-class navbar-message"><a href="<?php echo U('activity/index');?>">发现</a></div>
			<div class="navbar-class"><a href="<?php echo U('user/guide');?>">新手引导</a></div>
			<?php if(isset($_GET['wid'])): if(!empty($menu_url)): ?><div class="navbar-class"><a href="<?php echo ($menu_url); ?>"><?php echo ($menu_name); ?></a></div><?php endif; endif; ?>
		</div>
	</div>
</div>
  	<div class="top-backs">

	  	<div class="container">
			<!-- 左侧分类开始 -->
			<div class="left-class">
				<a href="<?php echo U('list/index/');?>"><span class="icon icon-star-empty"></span>全部商品</a>
				<?php $_result=R('list/type');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('list/index/?id='.$vo['id']);?>"><span class="<?php echo ($vo['icon']); ?>"></span><?php echo ($vo['title']); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<!-- 左侧分类结束 -->
			<!-- silde 开始 -->
			<div class="silde">
				<div id="focus">
			        <ul>
			        	<?php if(is_array($slider)): $i = 0; $__LIST__ = $slider;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($vo['link']); ?>" target="_blank"><img src="<?php echo get_cover($vo['cover_id'],'path');?>" alt="" /></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
			        </ul>
			    </div>
			</div>
			<!-- silde 结束 -->
			<!-- 推荐开始 -->
			<div class="notice">
				<?php if(is_array($news)): $num = 0; $__LIST__ = $news;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($num % 2 );++$num; if(($num) == "1"): ?><h4><?php echo ($vo["title"]); ?></h4>
				<div><?php echo stripslashes(htmlspecialchars_decode($vo['content']));?></div>
				<i><a href="<?php echo U('News/more?id='.$vo['id']);?>" class="a3">查看详细>></a></i>
				<?php else: ?>
				<span><a href="<?php echo U('News/more?id='.$vo['id']);?>"><?php echo ($vo["title"]); ?></a></span><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<!-- 推荐结束 -->
	  	</div>

	  	<div class="container">

			<!-- 最新揭晓开始 -->
			<div class="announced" id="announced" url="<?php echo U('user/announced');?>">
				<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo['state']) == "2"): ?><ul>
					<i class=" "></i>
					<span><img src="<?php echo ($vo['pic']); ?>"></span>
					<li class="anntitle"><a href="<?php echo ($vo['url']); ?>"><?php echo ($vo['name']); ?></a></li>
					<li><b>总需：<?php echo ($vo['price']); ?>人次</b></li>
					<li><b>期号：<?php echo ($vo['no']); ?></b></li>
					<li>获奖者：<a href="/user/user/id/<?php echo ($vo['uid']); ?>" class="a3"><?php echo ($vo['user']); ?></a></li>
					<li>本期参与：<?php echo ($vo['count']); ?>人次</li>
					<li>幸运号码：<?php echo ($vo['kaijang_num']); ?></li>
				</ul>
				<?php else: ?>
				<ul class="info" pid="<?php echo ($vo['pid']); ?>">
					<i class="active"></i>
					<span><img src="<?php echo ($vo['pic']); ?>"></span>
					<li class="anntitle"><a href="<?php echo ($vo['url']); ?>"><?php echo ($vo['name']); ?></a></li>
					<li><b>总需：<?php echo ($vo['price']); ?>人次</b></li>
					<li><b>期号：<?php echo ($vo['no']); ?></b></li>
					<li class="anntitle">揭晓倒计时：</li>
					<div class="countdown" diffe="<?php echo ($vo['kaijang_diffe']); ?>"></div>
				</ul><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<!-- 最新揭晓结束 -->
	  	</div>
	</div>

  	<div class="container">
  		<!-- 最新商品开始 -->
  		<div class="product-news">
  			<div class="list-title">
	  			<h3>最热商品</h3>
  			</div>
  			<div class="product-list">
  				<?php $_result=R('index/period',array(1,0,8));if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pro-list">
  					<?php if(($vo['position']) == $pos): ?><div class="float-title">推荐夺宝</div><?php endif; ?>
  					<?php if(!empty($vo['ten_name'])): ?><div class="float-title ten"><?php echo ($vo['ten_name']); ?></div><?php endif; ?>
					<div class="topimg"><a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo ($vo["pic"]); ?>" /></a></div>
					<div class="reco-ls"><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></div>
					<div class="reco-ls huise">总需：<?php echo ($vo["price"]); ?> 人次</div>
						<div class="progress">
						    <span class="orange" style="width: <?php echo ($vo["jd"]); ?>%;"></span>
						</div>
					<div class="reco-nb huise">
						<div class="reco-lnb"><?php echo ($vo["number"]); ?><br>已参与人次</div>
						<div class="reco-rnb"><?php echo ($vo['surplus']); ?><br>剩余人次</div>
					</div>
					<div class="reco-btn"><a href="<?php echo ($vo["url"]); ?>" class="btn btn-pink">立即夺宝</a></div>
  				</div><?php endforeach; endif; else: echo "" ;endif; ?>
  			</div>
  		</div>
  		<!-- 最新商品结束 -->

  		<!-- 狗屎运开始 -->
  		<div class="shit scroll_vertical pic_list_3">
  			<h3>好运榜</h3>
  			<div class="box">
  			<div class="list">
  			<?php if(is_array($lottery)): $i = 0; $__LIST__ = $lottery;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="shit-list">
  				<li class="users-imgs"><img src="<?php echo get_user_pic($vo['uid']);?>"></li>
  				<li class="users-name"><a href="<?php echo U('user/user?id='.$vo['uid']);?>" class="a3"><?php echo is_numeric(get_user_name($vo['uid']))?substr_replace(get_user_name($vo['uid']),'****',3,4):get_user_name($vo['uid']);?></a></li>
  				<li class="users-time"><span class="huise"><?php echo time_format($vo["kaijang_timing"],"于m月d日");?></span></li>
  				<li class="users-txt"><a href="<?php echo ($vo['url']); ?>"><?php echo ($vo["name"]); ?> (<small><?php echo ($vo["no"]); ?></small>期)</a></li>
  				<li class="users-txt users-txt2"><span class="huise">总需：<?php echo ($vo['price']); ?> 人次</span></li>
  			</ul><?php endforeach; endif; else: echo "" ;endif; ?>
  			</div>
  			</div>
  			<div class="shit-bottom">
				看看谁的狗屎运最好！
  			</div>
  		</div>
  		<!-- 狗屎运结束 -->
		
		<!-- 产品列表开始 -->
		<?php $_result=R('list/type');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?><div class="index-pro-list">
	  		<div class="list-title">
		  		<h3><?php echo ($type['title']); ?></h3>
		  		<div class="mores"><a href="<?php echo U('list/index/?id='.$type['id']);?>">更多商品，点击查看...</a></div>
	  		</div>
	  		<div class="product-list-all">
	  			<?php $_result=R('index/period',array(1,$type['id'],5));if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="pro-list-all">
  					<?php if(($vo['position']) == $pos): ?><div class="float-title">推荐夺宝</div><?php endif; ?>
  					<?php if(!empty($vo['ten_name'])): ?><div class="float-title ten"><?php echo ($vo['ten_name']); ?></div><?php endif; ?>
  					<div class="topimg"><a href="<?php echo ($vo["url"]); ?>"><img src="<?php echo ($vo["pic"]); ?>" /></a></div>
					<div class="reco-ls"><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["name"]); ?></a></div>
					<div class="reco-ls huise">总需：<?php echo ($vo["price"]); ?> 人次</div>
						<div class="progress">
						    <span class="orange" style="width: <?php echo ($vo["jd"]); ?>%;"></span>
						</div>
					<div class="reco-nb huise">
						<div class="reco-lnb"><?php echo ($vo["number"]); ?><br>已参与人次</div>
						<div class="reco-rnb"><?php echo ($vo['surplus']); ?><br>剩余人次</div>
					</div>
					<div class="reco-btn"><a href="<?php echo ($vo["url"]); ?>" class="btn btn-pink">立即夺宝</a></div>
  				</div><?php endforeach; endif; else: echo "" ;endif; ?>
  			</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
		<!-- 产品列表结束 -->

		<!-- 晒单分享开始 -->
		<div class="share">
	  		<div class="list-title">
		  		<h3>晒单分享</h3>
		  		<div class="mores"><a href="<?php echo U('user/displays');?>">更多分享，点击查看...</a></div>
	  		</div>
	  		<div class="share-nav scroll_vertical pic_list_4">
	  			<div class="box">
					<div class="list">
						<?php $_result=R('index/shared',array(1,''));if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><ul class="share-o">
							<li class="share-img"><img src="<?php echo ($vo['thumbpic'][0]); ?>"></li>
							<li class="share-navs">
								<div class="sharenavs"><?php echo ($vo['content']); ?></div>
								<div class="shareuser">—— <a href="<?php echo ($vo['user_url']); ?>" class="a3"><?php echo ($vo["name"]); ?></a> <?php echo ($vo['shared_time']); ?></div>
							</li>
						</ul><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
	  			</div>
	  		</div>
		</div>
		<!-- 晒单分享结束 -->
  	</div>
  	<div class="message-fixed">
		<div id="moquu_top">
			<div class="icons">
				<span class="top"></span>
				<b>返回顶部</b>
			</div>
		</div>
	</div>
  	<div class="clear"></div>
  	<div class="bottom">
	<div class="container">
		<div class="bot-left">
			<h3>
				<span class="icon icon-leanpub"></span>
				新手指南
			</h3>
			<ul>
				<li><a href="<?php echo U('news/more/id/1');?>">了解<?php echo ($web_title); ?>平台</a></li>
				<li><a href="<?php echo U('news/more/id/2');?>">服务协议</a></li>
				<li><a href="<?php echo U('news/more/id/3');?>">常见问题</a></li>
				<li><a href="<?php echo U('news/more/id/4');?>">推广赚钱</a></li>
			</ul>
		</div>
		<div class="bot-left">
			<h3>
				<span class="icon icon-dunpai"></span>
				欢乐保障
			</h3>
			<ul>
				<li><a href="<?php echo U('news/more/id/5');?>">公平保障</a>
				</li>
				<li><a href="<?php echo U('news/more/id/6');?>">公正保障</a></li>
				<li><a href="<?php echo U('news/more/id/7');?>">公开保障</a></li>
				<li><a href="<?php echo U('news/more/id/8');?>">安全支付</a></li>
			</ul>
		</div>
		<div class="bot-left">
			<h3>
				<span class="icon icon-truck"></span>
				商品配送
			</h3>
			<ul>
				<li><a href="<?php echo U('news/more/id/9');?>">配送费用</a></li>
				<li><a href="<?php echo U('news/more/id/10');?>">商品验收与签收</a></li>
				<li><a href="<?php echo U('news/more/id/11');?>">发货未收到商品</a></li>
				<li><a href="<?php echo U('news/more/id/12');?>">商品配送</a></li>
			</ul>
		</div>
		<div class="bot-left">
			<h3>
				<span class="icon icon-github"></span>
				关于我们
			</h3>
			<ul>
				<li><a href="<?php echo U('news/more/id/13');?>">关于我们</a></li>
				<?php if(empty($_GET['wid'])): ?><li><a href="<?php echo U('news/more/id/14');?>">公司证件</a></li><?php endif; ?>
			</ul>
		</div>
		<div class="bot-right">
			<div class="bot-gongping">
				<span class="icon icon-zhngpin"></span> <i>100%正品保证</i>
			</div>
			<div class="bot-gongping">
				<span class="icon icon-gongpin"></span> <i>100%公平公正公开</i>
			</div>
			<div class="bot-gongping">
				<span class="icon icon-gongzheng"></span>
				<i>100%权益保障</i>
			</div>
		</div>

		<div class="copyright">
			Copyright &copy; 2015 <?php echo ($web_title); ?> <?php echo ($web_url); ?> 版权所有 <?php echo ($web_icp); ?>
		</div>
	</div>
</div>
  	<script type="text/javascript">
  		var ThinkPHP = window.Think = {
			"APP"    : "/shop/index.php?s=",
			"PATTEM" : "<?php echo C('WEB_PATTEM');?>",
			"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>",
			"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
			"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        };
  		var htmltype,servertime=<?php echo ($web_time); ?>;
  	</script>
	<script src="<?php echo ($web_tplpath); ?>js/jquery.min.js"></script>
	<script src="<?php echo ($web_tplpath); ?>js/simplefoucs.js"></script>
	<script src="<?php echo ($web_tplpath); ?>js/jquery.downcount.min.js"></script>
	<script src="<?php echo ($web_tplpath); ?>js/jquery.cxscroll.min.js"></script>
	<script src="/shop/Public/Static/think.js"></script>
	<script src="<?php echo ($web_tplpath); ?>js/script.js"></script>
	<!--[if lt IE 9]>
	<script src="j<?php echo ($web_tplpath); ?>s/jquery.pseudo.js"></script>
	<![endif]-->
  </body>
</html>