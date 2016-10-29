(function() {
	var ThinkPHP = window.Think;

	$('.paybut').click(function() {
		var paytype = $('.pay-label.active span').data('paytype');
		$(this).prop('disabled', true);
		switch (paytype) {
			case 2:
				weixinPay(this);
				break;
			case 3:
				aliPay(this);
				break;
			case 5:
				yunPay(this);
				break;
			case 1025:
			case 1051:
			case 104:
			case 103:
			case 3407:
			case 3230:
			case 3080:
			case 313:
			case 314:
			case 309:
			case 305:
			case 312:
			case 307:
			case 311:
			case 310:
			case 3061:
			case 326:
			case 335:
			case 342:
			case 343:
			case 316:
			case 302:
			case 324:
			case 336:
			case 3341:
			case 344:
			case 317:
			case 401:
			case 402:
			case 403:
			case 404:
			case 1027:
			case 1054:
			case 106:
			case 1031:
			case 3011:
			case 3231:
			case 308:
			case 3131:
			case 3141:
			case 3091:
			case 3051:
			case 3121:
			case 3071:
			case 3112:
			case 306:
			case 3261:
			case 303:
			case 3241:
			case 334:
			case 3101:
			case 4031:
				bankPay(this,paytype);
				break;
			case 6:
				payPal(this);
				break;
			default:
				yuePay(this);
		}
	})

	function yuePay(event) {
		$.post(ThinkPHP.U("pay/yuepay"), {
			pid: $('input[name="pid"]').val(),
			price: $('input[name="price"]').val()
		}).success(function(data){
			if (data.url) {
				location.href = data.url;
			} else {
				layer.msg(data.info, {icon: 2});
				$(event).prop('disabled', false);
			}
		});
	}

	//调用微信JS api 支付
	function weixinPay(event) {
		if(!$('input[name="price"]').val()){
			layer.msg('请选择充值金额！', {icon: 2});
			$(event).prop('disabled', false);
			return false;
		}
		$.get(ThinkPHP.U("pay/pay_weixin",{"price":$('input[name="price"]').val(),"pid":$('input[name="pid"]').val()})).success(function(status){
			if(status.status){
				if(status.info.return_msg!="OK"){
					layer.msg(status.info.return_msg, {icon: 2,shade:0.8});
					$(event).prop('disabled', false);
					return false;
				}
				StandardPost(ThinkPHP.U("pay/pay_wx"),{code:status.info.code_url,price:status.info.price,no:status.info.no,pid:status.info.pid});	
			}else{
				layer.msg(status.info, {icon: 2});
				$(event).prop('disabled', false);
			}
		});
	}

	function StandardPost(url,args){
        var form = $("<form method='post'></form>");
        form.attr({"action":url});
        for (arg in args){
            var input = $("<input type='hidden'>");
            input.attr({"name":arg});
            input.val(args[arg]);
            form.append(input);
        }
        form.submit();
    }

    function aliPay(event){
    	$.ajaxSetup({  
		    async : false  
		});
    	$.get(ThinkPHP.U("pay/check_pay",{"price":$('input[name="price"]').val(),"pid":$('input[name="pid"]').val()})).success(function(status){
    		if(status.status){
    			layer.confirm('<h3>请在新开窗口完成支付！</h3>完成付款后根据您的情况进行以下操作！', {
					icon: 0,
		    		btn: ['完成支付','遇到问题，重新选择'] //按钮
				}, function(index){
				    location.href = ThinkPHP.U('user/index');
				}, function(index){
				    layer.close(index);
				    $(event).prop('disabled', false);
				});
				$('form').attr('action',ThinkPHP.U('pay/pay_alipay')).attr('target','_blank').submit();
    		}else{
    			layer.msg(status.info, {icon: 2});
    			$(event).prop('disabled', false);
    		}
    	});
	}

	function bankPay(event,pay_type){
		$.ajaxSetup({  
		    async : false  
		});
		$.get(ThinkPHP.U("pay/check_pay",{"price":$('input[name="price"]').val(),"pid":$('input[name="pid"]').val()})).success(function(status){
    		if(status.status){
				layer.confirm('<h3>请在新开窗口完成支付！</h3>完成付款后根据您的情况进行以下操作！', {
					icon: 0,
		    		btn: ['完成支付','遇到问题，重新选择'] //按钮
				}, function(index){
				    location.href = ThinkPHP.U('user/index');
				}, function(index){
				    layer.close(index);
				    $(event).prop('disabled', false);
				});
				$('form').append("<input value="+pay_type+" name='pay_type' type='hidden'>");
				$('form').attr('action',ThinkPHP.U('pay/pay_bank')).attr('target','_blank').submit();
			}else{
    			layer.msg(status.info, {icon: 2});
    			$(event).prop('disabled', false);
    		}
    	});
	}

	function yunPay(event){
		$.ajaxSetup({  
		    async : false  
		});
		$.get(ThinkPHP.U("pay/check_pay",{"price":$('input[name="price"]').val(),"pid":$('input[name="pid"]').val()})).success(function(status){
			if(status.status){
				layer.confirm('<h3>请在新开窗口完成支付！</h3>完成付款后根据您的情况进行以下操作！', {
					icon: 0,
		    		btn: ['完成支付','遇到问题，重新选择'] //按钮
				}, function(index){
				    location.href = ThinkPHP.U('user/index');
				}, function(index){
				    layer.close(index);
				    $(event).prop('disabled', false);
				});
				$('form').attr('action',ThinkPHP.U('pay/pay_yun')).attr('target','_blank').submit();
			}else{
    			layer.msg(status.info, {icon: 2});
    			$(event).prop('disabled', false);
    		}
    	});
	}

	function payPal(event){
    	$.ajaxSetup({  
		    async : false
		});
    	$.get(ThinkPHP.U("pay/check_pay",{"price":$('input[name="price"]').val(),"pid":$('input[name="pid"]').val()})).success(function(status){
    		if(status.status){
    			layer.confirm('<h3>请在新开窗口完成支付！</h3>完成付款后根据您的情况进行以下操作！', {
					icon: 0,
		    		btn: ['完成支付','遇到问题，重新选择'] //按钮
				}, function(index){
				    location.href = ThinkPHP.U('user/index');
				}, function(index){
				    layer.close(index);
				    $(event).prop('disabled', false);
				});
				$('form').attr('action',ThinkPHP.U('pay/pay_pal')).attr('target','_blank').submit();
    		}else{
    			layer.msg(status.info, {icon: 2});
    			$(event).prop('disabled', false);
    		}
    	});
	}


	$('.dollar').click(function() {
		$('.dollar').removeClass('active');
		$(this).addClass('active');
		$('input[name="price"]').val($(this).not(':contains("其他金额")').text());
	});

	$('input[name="price_ye"]').keyup(function(){
		$('input[name="price"]').val($(this).val());
	});

	$('.pay-title b').click(function(){
		$('.pay-title b').removeClass('active');
		$(this).addClass('active');
		var index = $(".pay-title b").index(this);
		$('.pay-nav').hide();
		$('.pay-nav').eq(index).show();
	});

	$('.pay-nav h6').click(function(){
		if($(this).children('i').hasClass('icon-angle-down')){
			$(this).children('i').removeClass('icon-angle-down').addClass('icon-angle-up').text(' 收起部分银行');
			$('.pay-nav:visible').find('.pay-label').show();
		}else{
			$(this).children('i').removeClass('icon-angle-up').addClass('icon-angle-down').text(' 展开更多银行');
			$('.pay-nav:visible').find('.pay-label:gt(9)').hide();
		}
	});

	$('.pay-label').click(function() {
		$('.pay-label').removeClass('active');
		$(this).addClass('active');
	});

	$('.pay-nav').find('.pay-label:gt(9)').hide();
	$('.pay-nav:not(:eq('+$(".pay-title b.active").index()+'))').hide();
}());