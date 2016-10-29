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
	
	<div class="page-head">
		<h2>系统信息</h2>
	</div>
	<div class="cl-mcont">
		<div class="row">
			<div class="col-sm-4 col-md-4 col-lg-6">
				<div class="block-flat">
					<div class="overflow-hidden"> <i class="fa fa-file-text fa-4x pull-left color-primary"></i>
						<h3 class="no-margin">商品</h3>
						<p class="color-primary">Shop</p>
					</div>
					<div class="row">
						<div class="col-sm-4 col-md-4 col-lg-6">
							<div class="content">
								<h2 class="no-margin">共计 <?php echo ($count['shop']); ?> 件</h2>
							</div>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-6">
							<div class="content">
								<h2 class="no-margin">今日开奖 <?php echo ($count['today_period']); ?> 期</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="block-flat">
					<div class="header">							
						<h3>热门商品前5</h3>
					</div>
					<div class="row">
						<div class="col-sm-4"><div id="ticket-chart" style="height:175px;"></div></div>
						<div class="col-sm-8 no-padding">
						<table class="no-borders no-strip padding-sm">
							<tbody class="no-border-x no-border-y">
							<?php if(is_array($count['shopCount'])): $i = 0; $__LIST__ = $count['shopCount'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top5): $mod = ($i % 2 );++$i;?><tr>
								<td style="width:15px;"><div class="legend" style="background:<?php echo ($top5['color']); ?>;"></div></td>
								<td style="width:80%;"><?php echo ($top5['name']); ?></td>
								<td class="text-right"><b><?php echo ($top5['count']); ?>期</b></td>
							  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						  </table>
						</div>
					</div>
				</div>
				<div class="block-flat">
					<div class="header">							
						<h3>24小时投注图</h3>
					</div>
					<div class="content">
						<div id="site_statistics2" style="height: 200px; padding: 0px; position: relative;"></div>							
					</div>
				</div>
			</div>
			<div class="col-sm-4 col-md-4 col-lg-6">
				<div class="block-flat">
					<div class="overflow-hidden"> <i class="fa fa-user fa-4x pull-left color-danger"></i>
						<h3 class="no-margin">用户</h3>
						<p class="color-danger">Users</p>
					</div>
					<div class="row">
						<div class="col-sm-4 col-md-4 col-lg-6">
							<div class="content">
								<h2 class="no-margin">共计 <?php echo ($count['user']); ?> 人</h2>
							</div>
						</div>
						<div class="col-sm-4 col-md-4 col-lg-6">
							<div class="content">
								<h2 class="no-margin">今日注册 <?php echo ($count['today_user']); ?> 人</h2>
							</div>
						</div>
					</div>
				</div>
				<div class="block-flat">
					<div class="header">							
						<h3>消费用户前5</h3>
					</div>
					<div class="row">
						<div class="col-sm-4"><div id="ticket-user" style="height:175px;"></div></div>
						<div class="col-sm-8 no-padding">
						<table class="no-borders no-strip padding-sm">
							<tbody class="no-border-x no-border-y">
							<?php if(is_array($count['user_buy'])): $i = 0; $__LIST__ = $count['user_buy'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user_buy): $mod = ($i % 2 );++$i;?><tr>
								<td style="width:15px;"><div class="legend" style="background:<?php echo ($user_buy['color']); ?>;"></div></td>
								<td><?php echo ($user_buy['nickname']); ?></td>
								<td class="text-right"><b><?php echo ($user_buy['count']); ?>人次</b></td>
							  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						  </table>
						</div>
					</div>
				</div>
				<div class="block-flat">
					<div class="header">							
						<h3>当月注册用户</h3>
					</div>
					<div class="content">
						<div id="site_reguser" style="height: 200px; padding: 0px; position: relative;"></div>							
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

	<script type="text/javascript" src="/shop/Public/Admin/js/jquery.flot/jquery.flot.min.js"></script>
	<script type="text/javascript" src="/shop/Public/Admin/js/jquery.flot/jquery.flot.pie.min.js"></script>

	<script type="text/javascript">
		$(function() {
		/*Pie Chart*/
        var data = [
		<?php if(is_array($count['shopCount'])): $i = 0; $__LIST__ = $count['shopCount'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top5): $mod = ($i % 2 );++$i;?>{ label: "<?php echo ($top5['name']); ?>", data: <?php echo ($top5['proportion']); ?>,color:"<?php echo ($top5['color']); ?>"},<?php endforeach; endif; else: echo "" ;endif; ?>
        ]; 
		$.plot('#ticket-chart', data, {
          series: {
            pie: {
              show: true,
              innerRadius: 0.5,
              shadow:{
                top: 5,
                left: 15,
                alpha:0.3
              },
              stroke:{
                width:0
              },
              label: {
                show: false
              },
              highlight:{
                opacity: 0.08
              }
            }
          },
          grid: {
            hoverable: true,
            clickable: true
          },
          legend: {
            show: false
          }
        });

    var pageviews = [
	<?php if(is_array($count['buytime'])): $i = 0; $__LIST__ = $count['buytime'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$buytime): $mod = ($i % 2 );++$i;?>[<?php echo ($buytime['hour']); ?>, <?php echo ($buytime['count']); ?>],<?php endforeach; endif; else: echo "" ;endif; ?>
    ];
		
	$.plot($("#site_statistics2"), [{
        data: pageviews,
        label: "时间"
      }
      ], {
        series: {
          bars: {
            show: true,
            barWidth: 0.6,
            lineWidth: 0,
            fill: true,
            hoverable: true,
            fillColor: {
              colors: [{
                opacity: 1
              }, {
                opacity: 1
              }
              ]
            } 
          },
          shadowSize: 2
        },
        legend:{
          show: false
        },
        grid: {
        labelMargin: 10,
          axisMargin: 10000,
          hoverable: true,
          clickable: true,
          tickColor: "rgba(0,0,0,0.15)",
          borderWidth: 0
        },
        colors: [ "#50ACFE"],
        xaxis: {
          ticks: 11,
          tickDecimals: 0
        },
        yaxis: {
          ticks: 6,
          tickDecimals: 0
        }
      });
	  
	  $("#site_statistics2").bind("plothover", function (event, pos, item) {
        var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
        if (item) {
          if (previousPoint != item.dataIndex) {
            previousPoint = item.dataIndex;
            $("#tooltip").remove();
            var x = item.datapoint[0],
            y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY,
            item.series.label + "" + x + " 投注 " + y);
          }
        } else {
          $("#tooltip").remove();
          previousPoint = null;
        }
      });

      var regviews = [
	<?php if(is_array($count['reguser'])): $i = 0; $__LIST__ = $count['reguser'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$reguser): $mod = ($i % 2 );++$i;?>[<?php echo ($reguser['day']); ?>, <?php echo ($reguser['count']); ?>],<?php endforeach; endif; else: echo "" ;endif; ?>
    ];

      $.plot($("#site_reguser"), [{
        data: regviews,
        label: "月"
      }
      ], {
        series: {
          bars: {
            show: true,
            barWidth: 0.6,
            lineWidth: 0,
            fill: true,
            hoverable: true,
            fillColor: {
              colors: [{
                opacity: 1
              }, {
                opacity: 1
              }
              ]
            } 
          },
          shadowSize: 2
        },
        legend:{
          show: false
        },
        grid: {
        labelMargin: 10,
          axisMargin: 10000,
          hoverable: true,
          clickable: true,
          tickColor: "rgba(0,0,0,0.15)",
          borderWidth: 0
        },
        colors: [ "#50ACFE"],
        xaxis: {
          ticks: 11,
          tickDecimals: 0
        },
        yaxis: {
          ticks: 6,
          tickDecimals: 0
        }
      });
	  
	  $("#site_reguser").bind("plothover", function (event, pos, item) {
      
        var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";

        if (item) {
          if (previousPoint != item.dataIndex) {
            previousPoint = item.dataIndex;
            $("#tooltip").remove();
            var x = item.datapoint[0],
            y = item.datapoint[1];
            showTooltip(item.pageX, item.pageY,
              x + item.series.label + y+"人注册");
          }
        } else {
          $("#tooltip").remove();
          previousPoint = null;
        }
      });
	  
	  var data = [
		<?php if(is_array($count['user_buy'])): $i = 0; $__LIST__ = $count['user_buy'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user_buy): $mod = ($i % 2 );++$i;?>{label: "<?php echo ($user_buy['nickname']); ?>", data: <?php echo ($user_buy['proportion']); ?>,color:"<?php echo ($user_buy['color']); ?>"},<?php endforeach; endif; else: echo "" ;endif; ?>
        ]; 
		$.plot('#ticket-user', data, {
          series: {
            pie: {
              show: true,
              innerRadius: 0.5,
              shadow:{
                top: 5,
                left: 15,
                alpha:0.3
              },
              stroke:{
                width:0
              },
              label: {
                show: false
              },
              highlight:{
                opacity: 0.08
              }
            }
          },
          grid: {
            hoverable: true,
            clickable: true
          },
          legend: {
            show: false
          }
        });
	  
	   function showTooltip(x, y, contents) {
	      $("<div id='tooltip'>" + contents + "</div>").css({
	        position: "absolute",
	        display: "none",
	        top: y + 5,
	        left: x + 5,
	        border: "1px solid #000",
	        padding: "5px",
	        'color':'#fff',
	        'border-radius':'2px',
	        'font-size':'11px',
	        "background-color": "#000",
	        opacity: 0.80
	      }).appendTo("body").fadeIn(200);
	    } 
    });
	</script>

</body>
</html>