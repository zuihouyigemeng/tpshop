$(function(){
	//ajax get请求
    $('.ajax-get').click(function(){
        var target;
        var that = this;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
						layer.msg(data.info + ' 页面即将自动跳转~', {icon: 1});
                    }else{
						layer.msg(data.info, {icon: 1});
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
							var callback = $(that).attr('callback');
							if(callback){
								eval(callback);
							}
                        }else{
                            location.reload();
                        }
                    },2000);
                }else{
					layer.msg(data.info, {icon: 2});
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            var callback = $(that).attr('callback');
                            if(callback){
                                eval(callback);
                            }
                        }
                    },2000);
                }
            });

        }
        return false;
    });
	//ajax post submit请求
    $('.ajax-post').click(function(){
        var target,query,form;
        var target_form = $(this).attr('target-form');
        var that = this;
        var nead_confirm=false;
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
            	form = $('.hide-data');
            	query = form.serialize();
            }else if (form.get(0)==undefined){
            	return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                	target = $(this).attr('url');
                }else{
                	target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })
                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.serialize();
            }else{
                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }
            $(that).attr('autocomplete','off').prop('disabled',true);
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    if (data.url) {
						layer.msg(data.info + ' 页面即将自动跳转~', {icon: 1});
                    }else{
						layer.msg(data.info, {icon: 1});
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else if( $(that).hasClass('no-refresh')){
                            $(that).prop('disabled',false);
							var callback = $(that).attr('callback');
							if(callback){
								eval(callback);
							}
                        }else{
                            location.reload();
                        }
                    },2000);
                }else{
					layer.msg(data.info, {icon: 2});
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }else{
                            $(that).prop('disabled',false);
                        }
                    },2000);
                }
            });
        }
        return false;
    });
	
	window.setValue = function (name, value){
		var first = name.substr(0,1), input, i = 0, val;
		if(value === "") return;
		if("#" === first || "." === first){
			input = $(name);
		} else {
			input = $("[name='" + name + "']");
		}
	
		if(input.eq(0).is(":radio")) { //单选按钮
			input.filter("[value='" + value + "']").each(function(){this.checked = true});
		} else if(input.eq(0).is(":checkbox")) { //复选框
			if(!$.isArray(value)){
				val = new Array();
				val[0] = value;
			} else {
				val = value;
			}
			for(i = 0, len = val.length; i < len; i++){
				input.filter("[value='" + val[i] + "']").each(function(){this.checked = true});
			}
		} else {  //其他表单选项直接设置值
			input.val(value);
		}
	};

    function updateAlert(text,c) {
            var top_alert = $('#top-alert');
            text = text||'default';
            c = c||false;
            if ( text!='default' ) {
                top_alert.find('p').text(text);
                if (top_alert.hasClass('show')) {
                } else {
                    top_alert.addClass('show').removeClass('hidden').slideDown(200);
                }
            } else {
                if (top_alert.hasClass('show')) {
                    top_alert.removeClass('show').addClass('hidden').slideUp(200);
                }
            }
            if ( c!=false ) {
                top_alert.removeClass('alert-success alert-info alert-warning alert-danger').addClass(c);
            }
    }

	function toggleSideBar(_this){
        var b = $("#sidebar-collapse")[0];
        var w = $("#cl-wrapper");
        var s = $(".cl-sidebar");
        
        if(w.hasClass("sb-collapsed")){
          $(".fa",b).addClass("fa-angle-left").removeClass("fa-angle-right");
          w.removeClass("sb-collapsed");
        }else{
          $(".fa",b).removeClass("fa-angle-left").addClass("fa-angle-right");
          w.addClass("sb-collapsed");
        }
        //updateHeight();
      }
	  
	  /*SubMenu hover */
        var tool = $("<div id='sub-menu-nav' style='position:fixed;z-index:9999;'></div>");
        
        function showMenu(_this, e){
          if(($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",_this).length > 0){   
            $(_this).removeClass("ocult");
            var menu = $("ul",_this);
            if(!$(".dropdown-header",_this).length){
              var head = '<li class="dropdown-header">' +  $(_this).children().html()  + "</li>" ;
              menu.prepend(head);
            }
            
            tool.appendTo("body");
            var top = ($(_this).offset().top + 8) - $(window).scrollTop();
            var left = $(_this).width();
            
            tool.css({
              'top': top,
              'left': left + 8
            });
            tool.html('<ul class="sub-menu">' + menu.html() + '</ul>');
            tool.show();
            
            menu.css('top', top);
          }else{
            tool.hide();
          }
        }

        $(".cl-vnavigation li").hover(function(e){
          showMenu(this, e);
        },function(e){
          tool.removeClass("over");
          setTimeout(function(){
            if(!tool.hasClass("over") && !$(".cl-vnavigation li:hover").length > 0){
              tool.hide();
            }
          },500);
        });
        
        tool.hover(function(e){
          $(this).addClass("over");
        },function(){
          $(this).removeClass("over");
          tool.fadeOut("fast");
        });
        
        
        $(document).click(function(){
          tool.hide();
        });
        $(document).on('touchstart click', function(e){
          tool.fadeOut("fast");
        });
        
        tool.click(function(e){
          e.stopPropagation();
        });
     
        $(".cl-vnavigation li").click(function(e){
          if((($("#cl-wrapper").hasClass("sb-collapsed") || ($(window).width() > 755 && $(window).width() < 963)) && $("ul",this).length > 0) && !($(window).width() < 755)){
            showMenu(this, e);
            e.stopPropagation();
          }
        });
	  
	  function init(){
		$(".cl-vnavigation li ul").each(function(){
			$(this).parent().addClass("parent");
		  });
		  
		  $(".cl-vnavigation li ul li.active").each(function(){
			$(this).parent().show().parent().addClass("open");
		  });
		  
		  $(".cl-vnavigation").delegate(".parent > a","click",function(e){
			$(".cl-vnavigation .parent.open > ul").not($(this).parent().find("ul")).slideUp(300, 'swing',function(){
			   $(this).parent().removeClass("open");
			});
			
			var ul = $(this).parent().find("ul");
			ul.slideToggle(300, 'swing', function () {
			  var p = $(this).parent();
			  if(p.hasClass("open")){
				p.removeClass("open");
			  }else{
				p.addClass("open");
			  }
			 $("#cl-wrapper .nscroller").nanoScroller({ preventPageScrolling: true });
			});
			e.preventDefault();
		  });
		$("#sidebar-collapse").click(function(){
			toggleSideBar();
		});
		
		$(".cl-toggle").click(function(e){
			var ul = $(".cl-vnavigation");
			ul.slideToggle(300, 'swing', function () {
			});
			e.preventDefault();
      	});
	}
	
	window.highlight_subnav = function(url){
		$('.cl-vnavigation').find('a[href="'+url+'"]').parents('ul').show().parent().addClass("open");
		$('.cl-vnavigation').find('a[href="'+url+'"]').parent().addClass("active");
	}
	$("body").css({opacity:1,'margin-left':0});
	init();
	$('.ttip, [data-toggle="tooltip"]').tooltip();
	$('[data-popover="popover"]').popover();
	$('.dropdown').on('shown.bs.dropdown', function () {
    	$(".nscroller").nanoScroller();
    });
});