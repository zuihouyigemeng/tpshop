$(function(){
	var page=2,run=true,loadajax=true,loadrecord=true,loadshare=true,loadlottery=true,loadrecharge=true,ThinkPHP = window.Think;

	/*tab标签切换*/
	function tabs(tabTit, on, tabCon) {
		$(tabCon).each(function() {
			$(this).children().eq(0).show();
		});
		$(tabTit).each(function() {
			$(this).children().eq(0).addClass(on);
		});
		$(tabTit).children().click(function() {
			$(this).addClass(on).siblings().removeClass(on);
			var index = $(tabTit).children().index(this);
			$(tabCon).children().eq(index).show().siblings().hide();
			//加载购买记录
			$(tabCon).children().eq(index).find('.record-list').is(function(){
				if(loadrecord){
					loadrecord=false;
					$(this).hide();
					record(1);
					//ajax购买记录查看号码
					$(".recordlist").on('click','.mores',function(){
						$(this).parents("div").addClass("active");
					});
					//ajax购买记录关闭查看号码
					$(".recordlist").on('click','.colse-nb',function(){
						$(this).parents("div").removeClass("active");
					});
					//滚动加载
					$(".recordScrollbar").mCustomScrollbar({
				    	setHeight:800,
				    	theme:'dark',
				    	callbacks:{
				        	onScroll:function(){
					            if(this.mcs.topPct>=95 && loadajax){           		
					            	loadajax=false;
					            	record($('.record-list:hidden').data('page'));
					            }
					        }
					    }
					});
				}
			});
			//加载晒单记录
			$(tabCon).children().eq(index).find('.share-list').is(function(){
				if(loadshare){
					loadshare=false;
					$(this).hide();
					displays(1);
					//滚动加载
					$(".shareScrollbar").mCustomScrollbar({
				    	setHeight:600,
				    	theme:'dark',
				    	callbacks:{
				        	onScroll:function(){
					            if(this.mcs.topPct>=95 && loadajax){           		
					            	loadajax=false;
					            	displays($('.share-list:hidden').data('page'));
					            }
					        }
					    }
					});
				}
			});
			//加载中奖记录
			$(tabCon).children().eq(index).find('.lottery-list').is(function(){
				if(loadlottery){
					loadlottery=false;
					$(this).hide();
					lottery(1);
					//滚动加载
					$(".lotteryScrollbar").mCustomScrollbar({
				    	setHeight:600,
				    	theme:'dark',
				    	callbacks:{
				        	onScroll:function(){
					            if(this.mcs.topPct>=95 && loadajax){           		
					            	loadajax=false;
					            	lottery($('.lottery-list:hidden').data('page'));
					            }
					        }
					    }
					});
				}
			});
			//加载充值记录
			$(tabCon).children().eq(index).find('#recharge').is(function(){
				if(loadrecharge){
					loadrecharge=false;
					recharge(1);
					//滚动加载
					$(".rechargeScrollbar").mCustomScrollbar({
				    	setHeight:600,
				    	theme:'dark',
				    	callbacks:{
				        	onScroll:function(){
					            if(this.mcs.topPct>=95 && loadajax){           		
					            	loadajax=false;
					            	recharge($('#recharge').data('page'));
					            }
					        }
					    }
					});
				}
			});
		});
	}

	//ajax晒单分享
	function share(){
		$.getJSON($('#share').attr('url'), {
	      p: page
	    }, function(json) {
	    	if (json) {
		        var str = "";
		        for (var i = 0; i < json.length; i++) {
		        	str += '<div class="grid-item">';
					str += '<div class="pic"><a href="'+json[i].url+'"><img class="notimg" src="'+json[i].pic[0]+'"></a></div>';
					str += '<div class="name"><a href="'+ThinkPHP.U("shop/over",{"id":json[i].pid})+'">'+json[i].shop_name+'</a></div>';
					str += '<div class="name">幸运号码：<b class="red">'+json[i].kaijang_num+'</b></div>';
					str += '<div class="post">';
					str += '<span>'+json[i].time+'</span>';
					str += '<p>'+json[i].content+'</p>';
					str += '</div></div>'
		        }
		        page++;
		        run=true;
		        $("#share").append(str).css({ opacity: 0 });
		        $('#share').imagesLoaded(function(){
		        	$('#share').animate({ opacity: 1 });  
		        	$('#share').masonry('reloadItems').masonry('layout');
		       	});
		        $(".grid-loding").remove();
	    	}else{
	    		$(".grid-loding").html('神马都没有了');
	    	}
	    });
	}
	//ajax商品列表
  	function shoplist() {
  		var order=$('.inline.active').attr('order');
	    $.getJSON($('#shoplist').attr('url'), {p: page,order:$('.inline.active').attr('order')}, function(json) {
	    	if (json) {
		        var str = "";
		        for (var i = 0; i < json.length; i++) {
		        	str += '<div class="ls-product-list">';
		        	if(json[i]['position']==pos){
		        		str += '<div class="float-title">推荐夺宝</div>';
		        	}
		        	if(json[i]['ten_name']){
		        		str += '<div class="float-title">推荐夺宝</div>';
		        		str += '<div class="float-title ten">'+json[i]['ten_name']+'</div>';
		        	}
		        	str += '<div class="topimg"><a href="' + json[i].url + '"><img src="' + json[i].pic + '" /></a></div>';
					str += '<div class="reco-ls"><a href="' + json[i].url + '">' + json[i].name + '</a></div>';
					str += '<div class="reco-ls huise">总需：' + json[i].price + ' 人次';
					if(json[i].ten_restrictions==1){
						str += '<b><i></i>限购'+json[i].ten_restrictions_num+'人次</b>';
					}
					str += '</div>';
					str += '<div class="progress"><span class="orange" style="width: ' + json[i].jd + '%;"></span></div>';
					str += '<div class="reco-nb huise">';
					str += '<div class="reco-lnb">' + json[i].number + '<br>已参与人次</div>';
					str += '<div class="reco-rnb">' + json[i].surplus + '<br>剩余人次</div>';
					str += '</div><form action="'+ThinkPHP.U("pay/index")+'" method="post" target="_blank">';
					str += '<div class="reco-bottom">';
					if(json[i].ten_restrictions_num-json[i].no_count <= 0 && json[i].ten_restrictions > 0 && typeof(json[i].user_no)!="undefined"){
						str += '<div id="divEmpty" class="No_shopping"><b></b><p class="red">您已达到限购数量,请下期继续购买！</p></div>';
					}else{
						str += '<div class="ro-goods">';
						str += '我要参与：<div class="ro-goods-inputs">';
						str += '<input value="' + json[i].pid + '" name="pid" type="hidden">';
						str += '<input type="type" value="' + json[i].ten_unit + '" price="' + json[i].price + '" unit="' + json[i].ten_unit + '" limit="' +(json[i].ten_restrictions_num-json[i].no_count)+ '" surplus="' + json[i].surplus + '" name="number">';
						str += '<a href="javascript:;" class="ro-jia"><span class="icon icon-minus"></span></a>';
						str += '<a href="javascript:;" class="ro-jian"><span class="icon icon-plus"></span></a>';
						str += '</div> 人次</div>';
						str += '<div class="reco-ls-btn"><button class="btn btn-pink" type="submit">立即夺宝</button></div>';
					}
					str += '</div></form></div>';
		        }
		        page++;
		        run=true;
		        $("#shoplist").append(str);
		        $(".grid-loding").remove();
	    	}else{
	    		$(".grid-loding").html('神马都没有了');
	    	}
	    });
	}
  	//ajax最新揭晓
  	function announced() {
	    $.getJSON($('#announced').attr('url'), {
	      p: page
	    }, function(json) {
	    	if (json) {
		        var str = "";
		        for (var i = 0; i < json.length; i++) {
				    str += '<div class="time-goods">';
				    str += '<div class="pic"><a href="'+ThinkPHP.U("shop/over",{"id":json[i].pid}) + '"><img src="' + json[i].pic + '"></a></div>';
				    str += '<div class="name"><a href="'+ThinkPHP.U("shop/over",{"id":json[i].pid}) + '">' + json[i].name + '</a></div>';
				    str += '<div class="name huise">总需：' + json[i].price + '人次</div>';
				    str += '<div class="name huise">期号：' + json[i].no + '</div>';
		        	if (json[i].state == 1) {
			            str += '<div class="info" pid='+json[i].pid+'>';
			            str += '<h1><span class="icon icon-clock"></span> 揭晓倒计时</h1>';
			            str += '<div class="countdown" diffe="' + json[i].kaijang_diffe + '"></div>';
			            str += '</div></div>';
		        	} else {
			            str += '<div class="info">';
			            str += '<div class="user-img"><a href="'+ThinkPHP.U("user/user",{"id":json[i].uid}) + '"><img src="' + json[i].user_pic + '"></a></div>';
			            str += '<div class="user-info">';
			            str += '<span>恭喜 <a href="'+ThinkPHP.U("user/user",{"id":json[i].uid}) + '" class="a3">' + json[i].user + '</a></span>';
			            str += '<span>幸运号码：<b class="red">' + json[i].kaijang_num + '</b></span>';
			            str += '<span>本期参与：<b class="red">' + json[i].count + '</b>人次</span>';
			            str += '<span>揭晓时间：' + json[i].kaijang_time + '</span>';
			            str += '<span><a href="'+ThinkPHP.U("shop/over",{"id":json[i].pid}) + '" class="label label-yellow">查看详情</a></span>';
			            str += '</div></div></div>';
		        	}
		        }
		        page++;
		        run=true;
		        $("#announced").append(str);
		        $(".grid-loding").remove();
		        $("#announced").find('.info .countdown:not(:has(span))').each(function(){
					var xthis=$(this);
					xthis.downCount({difference:xthis.attr('diffe'),now:servertime*1000},function(){
						announced_ajax(xthis);
					});
				});
	    	}else{
	    		$(".grid-loding").html('神马都没有了');
	    	}
	    });
	}

	//ajax读取购买记录
	function record(p){
		var ithis=$('.record-list:hidden');
		ithis.show();
		ithis.parents('.tabs-panel-item').append('<div class="loading"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
		var html=ithis.prop("outerHTML"),rhtml='';
		ithis.hide();
	    $.getJSON(ithis.attr('url'),{p: p},function(result){
			if(result){
				$.each(result['list'], function(num, list){
					var shtml=html;
					$.each(list, function(i, field){
						if($.isArray(field)){
							ithis.find('.each').is(function(){
								var sthis=$(this).html(),nhtml='';
								$.each(field, function(x, vo){
										var rstr=new RegExp("{{shop_"+i+"}}","g");
										nhtml+= sthis.replace(rstr,vo);
								});
								shtml=shtml.replace(sthis,nhtml);
							});
						}else{
							var rstr =new RegExp("{{shop_"+i+"}}","g");
							shtml = shtml.replace(rstr,field);	
						}
					});
					rhtml+=shtml;
			    });
			    ithis.data('page',p+1);
		    	$('.loading').remove();
		    	$('.record-list:last').after(rhtml);
		    	loadajax=true;
			}else{
				$('.loading').html('<span class="icon icon-attention"></span> 神马都没有了');
				setTimeout(function(){
					$('.loading').remove();
					loadajax=true;
				},2000);
			}
		});
	}

	//ajax读取晒单记录
	function displays(p){
		var ithis=$('.share-list:hidden');
		ithis.show();
		ithis.parents('.tabs-panel-item').append('<div class="loading"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
		var html=ithis.prop("outerHTML"),rhtml='';
		ithis.hide();
	    $.getJSON(ithis.attr('url'),{p: p},function(result){
			if(result){
				$.each(result, function(num, list){
					var shtml=html;
					$.each(list, function(i, field){
						if($.isArray(field)){
							ithis.find('.each').is(function(){
								if(i=="thumbpic"){
									var sthis=$(this).html(),nhtml='';
									$.each(field, function(x, vo){
										var rstr=new RegExp("{{shop_"+i+"}}","g");
										nhtml+= sthis.replace(rstr,vo);
									});
									var rstr=new RegExp('<span class="share-center"></span><img class="notimg" src="{{shop_thumbpic}}">',"g");
									shtml=shtml.replace(rstr,nhtml);
								}
							});
						}else{
							var rstr =new RegExp("{{shop_"+i+"}}","g");
							shtml = shtml.replace(rstr,field);	
						}
					});
					rhtml+=shtml;
			    });
			    ithis.data('page',p+1);
		    	$('.loading').remove();
		    	$('.share-list:last').after(rhtml);
		    	loadajax=true;
			}else{
				$('.loading').html('<span class="icon icon-attention"></span> 神马都没有了');
				setTimeout(function(){
					$('.loading').remove();
					loadajax=true;
				},2000);
			}
		});
	}

	//ajax读取中奖记录
	function lottery(p){
		var ithis=$('.lottery-list:hidden');
		ithis.show();
		ithis.parents('.tabs-panel-item').append('<div class="loading"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
		var html=ithis.prop("outerHTML"),rhtml='';
		ithis.hide();
	    $.getJSON(ithis.attr('url'),{p: p},function(result){
			if(result){
				$.each(result, function(num, list){
					var shtml=html;
					if(list['shared']!=1){
						shtml = shtml.replace(ithis.find('.shaderla').prop("outerHTML"),'');
					}
					$.each(list, function(i, field){
						var rstr =new RegExp("{{shop_"+i+"}}","g");
						shtml = shtml.replace(rstr,field);
					});
					var rstr =new RegExp("{{if (.*)}}((.|\\s)*?){{\/if}}","g");
					var shtmlif=shtml.match(rstr);
					if(shtmlif){
						$.each(shtmlif, function(i, ifhtml){
							var ifc=rstr.exec(shtml);
							if(ifc[1]=='null'){
								shtml = shtml.replace(ifc[0],'');
							}else{
								shtml = shtml.replace(ifc[0],ifc[2]);
							}
						})	
					}
					rhtml+=shtml;
			    });
			    ithis.data('page',p+1);
		    	$('.loading').remove();
		    	$('.lottery-list:last').after(rhtml);
		    	loadajax=true;
			}else{
				$('.loading').html('<span class="icon icon-attention"></span> 神马都没有了');
				setTimeout(function(){
					$('.loading').remove();
					loadajax=true;
				},2000);
			}
		});
	}

	//ajax 用户中心购买记录
  	function records(p) {
  		var state = arguments[1] || $('.label[state]').attr('state');
  		$('#records').parents('.tabs-panel-item').append('<div class="loading"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
	    $.get($('#records').attr('url'),{p:p,state:state}, function(json){
	    	if (json) {
		        var str = "";
		        $.each(json, function(i, field){
		        	str += '<tr><td class="col1">';
			        str += '<a target="_blank" href="'+field['url']+'"><img src="'+field['pic']+'" width="64" height="48" ></a>';
					str += '</td><td class="col2">';
				    str += '<p><a title="'+field['name']+'" class="a3" target="_blank" href="'+field['url']+'">'+field['name']+'</a></p>';
		            
					if(field['state']==1){
						str += '<div class="reco-ls user-ing huise">总需：'+field['price']+' 人次</div>';
						str += '<div class="progress  user-ing"><span class="orange" style="width: '+field['jd']+'%;"></span></div>';
						str += '<div class="reco-nb user-ing huise"><div class="reco-lnb">'+field['number']+'<br>已参与人次</div>';
						str += '<div class="reco-rnb">'+field['surplus']+'<br>剩余人次</div></div></td><td>'+field['no']+'</td>';
						str += '<td><span class="yellow">等待揭晓...</span></td>';
					    str += '<td width="20%"><p class="people">'+field['count']+'人次</p></td>';
					}else if(field['state']==2){
						str += '<p>总需：'+field['price']+'人次</p>';
			            str += '<p>获得者：<a href="'+field['userurl']+'" class="a3 nameeses">'+field['user']['name']+'</a>（本期共参与<strong class="red">'+field['user']['count']+'</strong>人次）</p>';
			            str += '<p>幸运号码：<strong class="red">'+field['kaijang_num']+'</strong></p>';
			            str += '<p>揭晓时间：'+field['kaijang_time']+'</p></td><td>'+field['no']+'</td>';
						str += '<td><span class="red">已揭晓</span></td>';
					    str += '<td width="20%"><p class="people">'+field['count']+'人次</p></td>';
					}else if(field['state']==3){
						str += '<div class="reco-ls user-ing huise">总需：'+field['price']+' 人次</div></td>';
						str += '<td>'+field['no']+'</td>';
						str += '<td><span class="red">已经下架</span></td>';
						str += '<td width="20%"><p class="people">'+field['count']+'人次</p>';
						str += '<p class="people red">款项已退回账户</p></td>';
					}else{
						str += '<div class="reco-ls user-ing huise">总需：'+field['price']+' 人次</div>';
						str += '<div class="progress  user-ing"><span class="orange" style="width: '+field['jd']+'%;"></span></div>';
						str += '<div class="reco-nb user-ing huise"><div class="reco-lnb">'+field['number']+'<br>已参与人次</div>';
						str += '<div class="reco-rnb">'+field['surplus']+'<br>剩余人次</div></div></td><td>'+field['no']+'</td>';
						str += '<td><span class="green">正在进行...</span></td>';
					    str += '<td width="20%"><p class="people">'+field['count']+'人次</p>';
					    if($('#records').attr('url').indexOf('/uid/')<=0){
					    	str += '<p class="people peopleadd"><a href="javascript:()" class="a3">追加参与人次</a></p>';
						    str += '<div class="ro-goods" style="display: none">';
							str += '<form action="/pay/index" method="post"><div class="ro-goods-inputs">';
							str += '<input value="'+field['pid']+'" name="pid" type="hidden"><input type="type" value="1" surplus="'+field['surplus']+'" name="number">';
							str += '<a class="ro-jia"><span class="icon icon-minus"></span></a>';
							str += '<a class="ro-jian"><span class="icon icon-plus"></span></a></div>';
							str += '<button class="btn btn-red zhuibtn" type="submit">确定</button></div></form>';
					    }
					    str += '</td>';
					}
					str += '<td><a href="'+field['url']+'" class="a3">详情</a></td></tr>';
		        });
		        $("#records").append(str).data('page',p+1);
		        $('.loading').remove();
		        loadajax=true;
	    	}else{
	    		$('.loading').html('<span class="icon icon-attention"></span> 神马都没有了');
				setTimeout(function(){
					$('.loading').remove();
					loadajax=true;
				},2000);
	    	}
	    });
	}
	//ajax 用户充值记录
  	function recharge(p) {
  		var paydate = arguments[1] || $('.label[state]').attr('paydate');
  		$('#recharge').parents('.tabs-panel-item').append('<div class="loading"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
	    $.get($('#recharge').attr('url'),{p:p,paydate:paydate}, function(json){
	    	if (json) {
		        var str = "";
		        $.each(json, function(i, field){
					str += '<tr>';
					str += '<td>'+field['order_id']+'</td>';
					str += '<td>'+field['time']+'</td>';
					str += '<td><span class="green">'+field['paytype']+'</span></td>';
					str += '<td><span class="green">'+field['recharge']+'</span></td>';
					str += '<td>'+field['number']+'</td>';
					str += '<td>'+field['number']+'</td>';
					str += '<td>'+field['code']+'</td>';
					str += '</tr>';
		        });
		        $("#recharge").append(str).data('page',p+1);
		        $('.loading').remove();
		        loadajax=true;
	    	}else{
	    		$('.loading').html('<span class="icon icon-attention"></span> 神马都没有了');
				setTimeout(function(){
					$('.loading').remove();
					loadajax=true;
				},2000);
	    	}
	    });
	}

	function announced_ajax(obg){
		$.getJSON($('#announced').attr('url'),{
			pid: obg.parents('.info').attr('pid')
		}, function(json) {
			if (json) {
				switch (htmltype) {
		        case 'announced':
		          	var str = '<div class="user-img"><a href="'+ThinkPHP.U("user/user",{"id":json[0].uid}) + '"><img src="' + json[0].user_pic + '"></a></div>';
		            str += '<div class="user-info">';
		            str += '<span>恭喜 <a href="'+ThinkPHP.U("user/user",{"id":json[0].uid}) + '" class="a3">' + json[0].user + '</a></span>';
		            str += '<span>幸运号码：<b class="red">' + json[0].kaijang_num + '</b></span>';
		            str += '<span>本期参与：<b class="red">' + json[0].count + '</b>人次</span>';
		            str += '<span>揭晓时间：' + json[0].kaijang_time + '</span>';
		            str += '<span><a href="'+ ThinkPHP.U("shop/over",{"id":json[0].pid})+ '" class="label label-yellow">查看详情</a></span>';
		            str += '</div></div>';
		            obg.parents('.info').html(str);
		          break;
		        case 'product':
		        	location.reload();
		          break;
		        default:
		        	var str = '<i class=" "></i>';
					str += '<span><img src="' + json[0].pic + '"></span>';
					str += '<li class="anntitle"><a href="' + json[0].url + '">' + json[0].name + '</a></li>';
					str += '<li><b>总需：' + json[0].price + '人次</b></li>';
					str += '<li><b>期号：' + json[0].no + '</b></li>';
					str += '<li>获奖者：<a href="'+ThinkPHP.U("user/user",{"id":json[0].uid}) + '" class="a3">' + json[0].user + '</a></li>';
					str += '<li>本期参与：' + json[0].count + '人次</li>';
					str += '<li>幸运号码：' + json[0].kaijang_num + '</li>';
					obg.parents('.info').html(str);
		    	}
			}
		})
	}

	//首页滚动展示
	$(".pic_list_3").cxScroll({direction:"bottom",speed:500,time:1500,plus:false,minus:false});
	$(".pic_list_4").cxScroll({direction:"top"});
	//tab切换
	tabs(".tabs-tab","current",".product-content");
	//倒计时
	$('#announced').is(function(){
		if(htmltype=='announced'){
			page=1;
			announced();
		}
	});
	$('.countdown').each(function(){
		var xthis=$(this);
		xthis.downCount({difference:xthis.attr('diffe'),now:servertime*1000},function(){
			// xthis.html('<h5 style="font-size:22px; margin-left:10px;">正在开奖...</h5>');
			// xthis.removeClass('countdown countdownHolder');
			announced_ajax(xthis);
		});
	});
	// $container.imagesLoaded()
	// .always( function(instance,image){
 //    	console.log('all images loaded');
 //  	})
	// .progress(function(instance,image){
 //    	var $item = $( image.img ).parent();
	// 	$item.removeClass('is-loading');
	// 	if ( !image.isLoaded ) {
	// 		$item.addClass('is-broken');
	// 	}
 //  	});
	// $("img:not([class='notimg'])").LoadImage("/Public/Home/images/loading.jpg");
	//ajax 加载开奖时间计算用户
	$('.ajax-calculate').is(function(){
		var ithis=$(this),html=ithis.prop("outerHTML"),rhtml='';
		ithis.hide();
		ithis.after('<tr class="calcRow ajaxloading"><td colspan="5"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</td></tr>');
		$.getJSON(ithis.attr('url'),function(result){
			if(result['kjtime']){
			$.each(result['kjtime'], function(num, kjtime){
				var shtml=html;
				$.each(kjtime, function(i, field){
					var rstr =new RegExp("{{shop_"+i+"}}","g");
					shtml = shtml.replace(rstr,field);
				});
				rhtml+=shtml;
		    });
		    	$('.ajaxloading').remove();
		    	ithis.after(rhtml);
			}else{
				$('.ajaxloading').remove();
				ithis.after('<tr class="calcRow" style="font-size:18px;"><td colspan="5"><span class="icon icon-attention"></span> 暂时没有数据等待开奖</td></tr>');
			}
		});
	});

	$('.inline').click(function(event){
		page=1,run=false;
		$('#shoplist').empty();
		$(this).addClass('active').siblings().removeClass('active');
		shoplist();
	});

	//ajax 加载最新产品期
	$('.in-shop').is(function(){
		var ithis=$(this),html=ithis.html();
		ithis.hide();
		ithis.after('<div class="por-block"></div>');
		ithis.next().append('<span class="icon icon-spin3 animate-spin"></span> 正在努力加载...');
		$.getJSON(ithis.attr('url'),function(result){
			if(result['pid']){
				$.each(result, function(i, field){
					var rstr =new RegExp("{{shop_"+i+"}}","g");
					html = html.replace(rstr,field);
			    });
			    ithis.next().html(html);
			}else{
				ithis.next().html('产品已下架');
			}
		});
	});

	//ajax 加载往期中奖
	$('.history').is(function(){
		var shop_no=$(this).attr('no');
		var max_no=$(this).attr('maxno');
		var min_no=$(this).attr('minno');
		history($(this),'<'+shop_no);
		$(this).parent().on('click','.pbn-left',function(){
			if($(this).parent().attr('no')<max_no){
				history($('.history:hidden'),'>'+$(this).parent().attr('no'));
			}
		})
		$(this).parent().on('click','.pbn-right',function(){
			if($(this).parent().attr('no')>min_no){
				history($('.history:hidden'),'<'+$(this).parent().attr('no'));
			}	
		})
	});
	$('#records').is(function(){
		records(1);
		$(".recordsScrollbar").mCustomScrollbar({
	    	setHeight:600,
	    	theme:'dark',
	    	callbacks:{
	        	onScroll:function(){
		            if(this.mcs.topPct>=95 && loadajax){           		
		            	loadajax=false;
		            	records($('#records').data('page'));
		            }
		        }
		    }
		});
	});
	//ajax 往期数据
	function history(ithis,no){
		var html=ithis.html();
		ithis.hide();
		if(ithis.next().length<=0){
			ithis.after('<div class="por-block pro-block1"></div>');
		}
		ithis.next().html('<span class="icon icon-spin3 animate-spin"></span> 正在努力加载...');
		$.getJSON(ithis.attr('url'),{no:no},function(result){
			if(result){
				$.each(result, function(i, field){
					$.each(field, function(x, rs){
						var rstr =new RegExp("{{shop_"+x+"}}","g");
						html = html.replace(rstr,rs);
					});
			    });
			    ithis.next().html(html);
			}else{
				ithis.next().html('<span class="icon icon-attention"></span> 暂时没有数据');
			}
		});
	}


	//增加购买数量
    $('body').on('click','.ro-jia',function(){
        var num=parseInt($(this).siblings('input[name="number"]').val());
        var unit=parseInt($(this).siblings('input[name="number"]').attr('unit'));
        var price=parseInt($(this).siblings('input[name="number"]').attr('price'));
        if(num>unit){
          $(this).siblings('input[name="number"]').val(num-unit);
          $('.win_prob').html('<span class="win_txt">获得几率'+changeTwoDecimal_f(num-unit/price*100)+'%<i></i></span>').show();
			setTimeout(function(){$('.win_prob').fadeOut()},3000)
        }
    });
    $('body').on('click','.ro-jian',function(){
        var num=parseInt($(this).siblings('input[name="number"]').val());
        var unit=parseInt($(this).siblings('input[name="number"]').attr('unit'));
        var price=parseInt($(this).siblings('input[name="number"]').attr('price'));
        var limit=parseInt($(this).siblings('input[name="number"]').attr('limit'));
        var surplus=parseInt($(this).siblings('input[name="number"]').attr('surplus'));
        var val=num+unit;
        if(limit>0 && val>limit){
        	val=limit;
        }
        if(num<surplus){
	   		$(this).siblings('input[name="number"]').val(val);
	    }
        $('.win_prob').html('<span class="win_txt">获得几率'+changeTwoDecimal_f(val/price*100)+'%<i></i></span>').show();
		setTimeout(function(){$('.win_prob').fadeOut()},3000)
    });
    $('body').on('blur','input[name="number"]',function(){
    	var unit=parseInt($(this).attr('unit'));
    	var price=parseInt($(this).attr('price'));
		var num=parseInt($(this).val());
		var limit=parseInt($(this).attr('limit'));
        var val=Math.ceil(num/unit)*unit;
        if(limit>0 && val>limit){
        	val=limit;
        }
		$(this).val(val);
		$('.win_prob').html('<span class="win_txt">获得几率'+changeTwoDecimal_f(val/price*100)+'%<i></i></span>').show();
		setTimeout(function(){$('.win_prob').fadeOut()},3000)
	});
    $('body').on('submit','form',function(){
        var surplus = parseInt($(this).find('input[name="number"]').attr('surplus'));
        var price = parseInt($(this).find('input[name="number"]').val());
        var limit=parseInt($(this).find('input[name="number"]').attr('limit'));
        if(price<1){
            $(this).find('input[name="number"]').val(1);
        }
        if(limit>0 && price>limit){
        	$(this).find('input[name="number"]').val(limit);
        }
        if(price>surplus){
            $(this).find('input[name="number"]').val(surplus);
        }
    });
	$('.renci_sz').click(function(event) {
		var val=$(this).attr('val');
		var price=$('input[name="number"]').attr('price');
		$('input[name="number"]').val(val);
		$('.win_prob').html('<span class="win_txt">获得几率'+changeTwoDecimal_f(val/price*100)+'%<i></i></span>').show();
		setTimeout(function(){$('.win_prob').fadeOut()},3000)
	});
    $('[state]').click(function(event){
    	$('#records').empty();
    	$('[state]').removeClass('label label-red');
    	$(this).addClass('label label-red')
    	records(1,$(this).attr('state'));
    });
    $('[paydate]').click(function(event){
    	$('#recharge').empty();
    	$('[paydate]').removeClass('label label-red');
    	$(this).addClass('label label-red')
    	recharge(1,$(this).attr('paydate'));
    });

    $("#moquu_top").click(function() {
		$(document).scrollTop(0)
	})

	//ajax加载
	$(window).scroll(function () {
		var srollPos = $(window).scrollTop();
		var windowHeight = $(window).height(); //窗口的高度
		var dbHiht = $("body").height(); //整个页面文件的高度
		if (srollPos > 103) {
			$('.category').addClass('nav-fixed');
		}else{
			$('.category').removeClass('nav-fixed');
		}
		srollPos > windowHeight ? $("#moquu_top").show() : $("#moquu_top").hide()

		if((windowHeight + srollPos) >= (dbHiht) && run){
			run=false;
			$("#"+htmltype).after('<div class="clear"></div><div class="grid-loding"><span class="icon icon-spin3 animate-spin"></span> 正在努力加载...</div>');
			switch (htmltype) {
		        case 'share':
		        	share();
		          break;
		        case 'shoplist':
					shoplist();
		          break;
		        case 'announced':
		         	announced();
		          break;
		        default:
		    }
		}
			
	});

	function changeTwoDecimal_f(x) {
	    var f_x = parseFloat(x);
	    if (isNaN(f_x)) {
	        return false;
	    }
	    var f_x = Math.round(x * 100) / 100;
	    var s_x = f_x.toString();
	    var pos_decimal = s_x.indexOf('.');
	    if (pos_decimal < 0) {
	        pos_decimal = s_x.length;
	        s_x += '.';
	    }
	    while (s_x.length <= pos_decimal + 2) {
	        s_x += '0';
	    }
	    return s_x;
	}
});
