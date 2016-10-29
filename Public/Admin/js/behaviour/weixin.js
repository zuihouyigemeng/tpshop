	var index = parent.layer.getFrameIndex(window.name);
		   
	$('.gallery-cont').on('click','.imgdel',function(){
		$(this).parents('.cl-mcont').next().show();											 
		$(this).parents('.gallery-cont').children().remove();
		$('#media_id').val('');
		$('#data_list').val('');
	});	
	
	$('.addimage').click(function(){
		var url=$(this).parents('.over').prev('img').attr('src');
		parent.$("#media_id").val($(this).attr('media_id'));
		if(show==1){
			parent.$('#imageArea').find('.gallery-cont').append(addimage(url));
			parent.$(".upimage").hide();
		}else{
			parent.$('.img').children().eq(parent.$('#index').val()).find('img').attr('src',url);
		}
		parent.layer.close(index);
	});
	
	$('.addaudio').click(function(){
		var name=$(this).parents('.img').find('p:eq(0)').text();
		parent.$("#media_id").val($(this).attr('media_id'));
		parent.$('#audioArea').find('.gallery-cont').append(addaudio(name));
		parent.$(".upaudio").hide();
		parent.layer.close(index);
	});
	
	$('.addvideo').click(function(){
		var name=$(this).parents('.img').find('p:eq(0)').text();
		parent.$("#media_id").val($(this).attr('media_id'));
		parent.$('#videoArea').find('.gallery-cont').append(addvideo(name));
		parent.$(".upvideo").hide();
		parent.layer.close(index);
	});
	
	$('.addnews').click(function(){
		parent.$("#media_id").val($(this).attr('media_id'));
		parent.$('#appmsgArea').find('.gallery-cont').append(addnews(this));
		parent.$(".upnews").hide();
		if(send==1){
			parent.$("#data_list").val($(this).parent().next('input').val());
		}
		parent.layer.close(index);
	});
	
	$('.addmsg').click(function(){
		if(index){
			parent.location.href=$(this).attr('data-url');
			parent.layer.close(index);
		}else{
			location.href=$(this).attr('data-url');
		}
	});
	
	function addimage(url){
		var html='<div class="item col-sm-4 col-md-4 col-lg-3"><div class="photo">';
		html+='<div class="head imghg"> <span class="pull-right"><i class="fa fa-trash-o fa-lg color imgdel"></i> </span>';
		html+='</div><div class="img"> <img src="'+url+'" /> </div></div></div>';
		return html;
	}
	
	function addaudio(name){
		var html='<div class="item wximg"><div class="container-fluid wxls white-box">';
		html+='<div class="head imghg"><span class="pull-right">';
		html+='<i class="fa fa-trash-o fa-lg color imgdel"></i></span></div>';
		html+='<div class="col-md-3 audio"><i class="fa fa-rss"></i></div>';
		html+='<div class="col-md-8"><p>'+name+'</p></div>';
		html+='</div></div>';
		return html;
	}
	
	function addvideo(name){
		var html='<div class="item wximg"><div class="container-fluid wxls white-box">';
		html+='<div class="head imghg"><span class="pull-right">';
		html+='<i class="fa fa-trash-o fa-lg color imgdel"></i></span></div>';
		html+='<div class="col-md-3 video"><i class="fa fa-play-circle"></i></div>';
		html+='<div class="col-md-8"><p>'+name+'</p></div>';
		html+='</div></div>';
		return html;
	}
	
	function addnews(obj){
		var html='<div class="col-md-6 spacer-bottom-sm item w2"><div class="container-fluid wxls white-box">';
		html+='<div class="head imghg"><span class="pull-right">';
		html+='<i class="fa fa-trash-o fa-lg color imgdel"></i></span></div>';
		html+='<div class="img container-fluid no-padding">';
		html+='<p>'+$(obj).parents(".over").prevAll("p").text()+'</p><div class="tw">';
		html+='<div class="wxtitle">'+$(obj).parents(".img").find(".wxtitle").text()+'</div>';
		html+='<img src="'+$(obj).parents(".img").find(".tw img").attr('src')+'" class="img-responsive"></div>';
		$(obj).parents(".img").find(".wxdtw").each(function(){
		html+='<div class="wxdtw"><div class="wxtwtitle">';
		html+='<h5>'+$(this).find("h5").text()+'</h5></div>';
		html+='<div class="wxtwimg">';
		html+='<img src="'+$(this).find("img").attr('src')+'" class="img-responsive">';
		html+='</div></div>';
		});
		html+='</div></div></div>';
		return html;
	}
	
	$('.opiframe').click(function(){
		layer.open({
			type: 2,
			title: $(this).attr('data-name'),
			shadeClose: true,
			maxmin: false, //开启最大化最小化按钮
			area: ['850px', '610px'],
			content: [$(this).attr('url'), 'no']
		});
	});
	
	function uploadPicture(file, data){
		var data = $.parseJSON(data);
		if(data.status){
			if(data.type=="image"){
				var callback=eval('add'+data.type+'("'+data.url+'")');
			}else{
				var callback=eval('add'+data.type+'("'+data.name+'")');	
			}
			$("#media_id").val(data.media_id);
			$('#'+data.type+'Area').find('.gallery-cont').append(callback);
			$(".up"+data.type).hide();
		} else {
			layer.msg(data.info, {icon: 2});
			setTimeout(function(){
				$(that).prop('disabled',false);
			},1500);
		}
	}
	
var editdata={
	content:new Array(),
	edit:function(data,i,x){
		if(editdata.content[x]){
			editdata.content.splice(x,1,editdata.format(data));			
		}else{
			editdata.content.push(editdata.format(data));	
		}
		if(editdata.content[i]){
			return editdata.content[i];
		}else{
			return false;	
		}
	},
		
	format:function(data){
		var param = {};
		var check=false;
		$.each(data, function(k, v) {
			if(v.name=="content"){
				param[v.name]=v.value.replace(/\"/g,"'");
			}else{
				param[v.name]=v.value;
			}
			if(v.name=="show_cover_pic"){
				check=true;	
			}
		});	
		if(check==false){
			param['show_cover_pic']=0;
		}
		return param;
	},
	
	input:function(data){
		if(data){
			$.each(data, function(k, v) {
				if(k=='content'){
					ue.setContent(v);
					$('[name='+k+']').val(v);
				}else if(k=='show_cover_pic'){
					if(v!=1){
						$('[name='+k+']').iCheck('uncheck');
						$(this).attr("checked", false);
					}else{
						$('[name='+k+']').iCheck('check');
						$(this).attr("checked", true);
					}
				}else{
					$('[name='+k+']').val(v);
				}
			});
		}else{
			$("form :input").each(function(){
				if($(this).attr('name')=='content'){
					ue.setContent('');
				}else if($(this).attr('name')=='show_cover_pic'){
					$(this).iCheck('uncheck');
					$(this).attr("checked", false);
				}else{
					$(this).val('');
				}
			});
		}
	},
	html:function(img){
		var html='<div class="wxdtw"><div class="wxtwtitle">标题</div>';
		html+='<div class="col-md-3"><img src="'+img+'/suolue.jpg" class="img-responsive"></div>';
		html+='<div class="appmsg_edit_mask"><a href="javascript:" class="newsedit"><i class="fa fa-pencil"></i></a>';
		html+='<a href="javascript:" class="newsdel"><i class="fa fa-trash-o"></i></a></div></div>';
		return html;
	},
	check:function(){
		var ts=true;
		var form=$('.form-horizontal').serializeArray();
		var i = $('#index').val();
		editdata.edit(form,i,i);
		$('p.color-danger').remove();
		$.each(editdata.content, function(k, v){
			if(!v.title || v.title.length>64){
				$('[name="title"]').parents('.form-group').children('label').after('<p class="color-danger">标题不能为空且长度不能超过64字</p>');
				editdata.efocus(k);
				ts=false;
			}
			if(!v.thumb_media_id){
				$('[name="thumb_media_id"]').parents('.form-group').children('label').after('<p class="color-danger">必须插入一张图片</p>');
				editdata.efocus(k);
				ts=false;
			}
			if(!v.content || v.title.length>20000){
				$('[name="content"]').parents('.form-group').children('label').after('<p class="color-danger">正文不能为空且长度不能超过20000字</p>');
				editdata.efocus(k);
				ts=false;
			}
			if(ts){
				layer.msg('提交失败请检测未符合规则的项目');
				return;
			}
		});
		return true;
	},
	submitajax:function(url){
		if(editdata.check()){
			var index = layer.msg('正在提交', {icon: 16});
			$(".ajax-tj").attr('disabled',"true");
			$.post(url,{content:JSON.stringify(editdata.content),media_id:$('#mid').val()}).success(function(data){
				layer.close(index);
				$(".ajax-tj").removeAttr("disabled");
				if(data.status==1){
					if(data.url){
						layer.msg(data.info + ' 页面即将自动跳转~', {icon: 1});
						location.href=data.url;
					}
				}else{
					layer.msg(data.info, {icon: 2});
				}
			});
		}
	}
	,
	efocus:function(i){
		editdata.input(editdata.content[i]);
		$('#index').val(i);
		var top=$('.img').children().eq(i).offset().top-$('.tw').offset().top+"px";
		$('.editor').css("margin-top",top);	
	}
}

function uploadmsg(file, data){
	var data = $.parseJSON(data);
	if(data.status){
		$("#media_id").val(data.media_id);
		$('.img').children().eq($('#index').val()).find('img').attr('src',data.url);
	} else {
		layer.msg(data.info, {icon: 2});
		setTimeout(function(){
			$(that).prop('disabled',false);
		},1500);
	}
}
$('.ajax-tj').click(function(){
	editdata.submitajax($(this).attr('url'));
	//$('#newscontent').val(JSON.stringify(editdata.content));
	//$('.form-horizontal').submit();
});

var menu={
	jdList:"",
	init:function(){
		$('#list').nestable({
			dropCallback: function(e){
				var top = nestable_list.find('>.dd-list>.dd-item');
				$(top).each(function(){
					if($(this).find('.dd-item').size()>5){
						layer.msg("二级菜单最多只能设置5个", {icon: 2});
						return false;	
					}
				});
			}
		});
		$('.addnav').click(function(){
			var num=$("#list").children("ol").children("li").length;
			if(num>=3){
				layer.msg("最多可创建3个一级菜单", {icon: 2});
				return false;
			}else{
				var html="";
				html+='<li class="dd-item"><div class="pull-right value">';
				html+='<a href="javascript:;" class="addsub"><i class="fa fa-plus"></i></a> ';
				html+='<a href="javascript:;" id="editmenu"><i class="fa fa-pencil"></i></a> ';
				html+='<a id="delnav" href="javascript:;"><i class="fa fa-trash-o"></i></a> ';
				html+='</div><div class="dd-handle">未命名</div><input name="data" type="hidden"></li>';
				if($("#edit").has('div').length){
					layer.msg("请先完成当前栏目设置后再添加", {icon: 2});
					return false;
				}
				$("#list").children().append(html);
				$("#edit").append(menu.navhtml());
				$('#edit').find('#keyword').show();
				$('#edit').find('#news').hide();
				$('#edit').find('#url').hide();
				menu.jdList=$("#list").find('li').last();
			}
		});
		$(".dd-list").on('click','.addsub',function(){
			var num=$(this).parent().siblings('ol').children("li").length;
			if(num>=5){
				layer.msg("最多可创建5个二级级菜单", {icon: 2});
				return false;
			}else{
				if(!num){
					$(this).parents('li').append('<ol class="dd-list"></ol>');
				}
				var html="";
				html+='<li class="dd-item"><div class="pull-right value">';
				html+='<a href="javascript:;" class="addsub"><i class="fa fa-plus"></i></a> ';
				html+='<a href="javascript:;" id="editmenu"><i class="fa fa-pencil"></i></a> ';
				html+='<a id="delnav" href="javascript:;"><i class="fa fa-trash-o"></i></a> ';
				html+='</div><div class="dd-handle">未命名</div><input name="data" type="hidden"></li>';
				if($("#edit").has('div').length){
					layer.msg("请先完成当前栏目设置后再添加", {icon: 2});
					return false;
				}
				$(this).parent().siblings('ol').append(html);
				$(this).parent().siblings('ol').children("li").last().find('.addsub').hide();
				$("#edit").append(menu.navhtml());
				$('#edit').find('#keyword').show();
				$('#edit').find('#news').hide();
				$('#edit').find('#url').hide();
				menu.jdList=$(this).parent().siblings('ol').children("li").last();
			}
		});
		
		$(".dd-list").on('click','#editmenu',function(){
			var obj=$(this).parent().parent("li");
				if($("#edit").has('div').length){
					$('#edit').empty();
				}
				$("#edit").append(menu.navhtml());
				$('#edit input[name="title"]').val(obj.data("name"));
				$('#edit input[name="key"]').val(obj.data("key"));
				$('#edit input[name="url"]').val(obj.data("url"));
				$('#edit input[name="media_id"]').val(obj.data("media_id"));
				$('#edit select[name="type"]').val(obj.data("type"));
				$('#edit select[name="type"]').change();
				menu.jdList=obj;
		});
		
		$(".dd-list").on('click','#delnav',function(){
			var dangqian=$(this).parent().parent('li');
			if(dangqian.siblings().length>0){
				dangqian.remove();
			}else{
				dangqian.parent().siblings('[data-action]').remove();
				dangqian.parent().remove();
			}
		});
		
		$('#edit').on('change', 'select[name="type"]', function(){
			switch($(this).val()){
			case "click":
			  $('#edit').find('#keyword').show();
			  $('#edit').find('#news').hide();
			  $('#edit').find('#url').hide();
			  break;
			case "view":
			  $('#edit').find('#keyword').hide();
			  $('#edit').find('#news').hide();
			  $('#edit').find('#url').show();
			  break;
			case "scancode_push":
			case "scancode_waitmsg":
			case "pic_sysphoto":
			case "pic_photo_or_album":
			case "pic_weixin":
			case "location_select":
				$('#edit input[name="key"]').val($(this).val());
			  $('#edit').find('#keyword').hide();
			  $('#edit').find('#news').hide();
			  $('#edit').find('#url').hide();
			  break;
			default:
			  $('#edit').find('#keyword').hide();
			  $('#edit').find('#news').show();
			  $('#edit').find('#url').hide();
			}
		});
		$('#edit').on('click', 'button', function(){
			var title = $('input[name="title"]', $('#edit')).val();
			var type = $('select[name="type"]', $('#edit')).val();
			var key = $('input[name="key"]', $('#edit')).val();
			var url = $('input[name="url"]', $('#edit')).val();
			var media_id = $('input[name="media_id"]', $('#edit')).val();
			if(title=='' || (key=='' && type=="click") || (url=='' && type=="view") || (media_id=='' && type=="media_id")){
				layer.msg("请填写完整菜单参数", {icon: 2});
				return false;
			}

			if(menu.getStrleng(title)>12){
				layer.msg("菜单名称的长度过长", {icon: 2});
				return false;	
			}
			menu.jdList.children('.dd-handle').text(title);
			menu.jdList.data("type",type);
			menu.jdList.data("name",title);
			menu.jdList.data("key",key);
			menu.jdList.data("url",url);
			menu.jdList.data("media_id",media_id);
			$('#edit').empty();
		});
		$(".btn-primary").click(function(){
			$.post($(this).attr('url'),{data:JSON.stringify($("#list").nestable('serialize'))}).success(function(data){
				if(data.status==1){
					layer.msg(data.info, {icon: 1});
				}else{
					layer.msg(data.info, {icon: 2});
				}
			});
		});
		$("#edit").on('click','.opiframe',function(){
			layer.open({
				type: 2,
				title: $(this).attr('data-name'),
				shadeClose: true,
				maxmin: false, //开启最大化最小化按钮
				area: ['850px', '610px'],
				content: [$(this).attr('url'), 'no']
			});
		});
		$('#edit').on('click','.imgdel',function(){
			$(this).parents('.cl-mcont').next().show();											 
			$(this).parents('.gallery-cont').children().remove();
			$('#media_id').val('');
			$('#data_list').val('');
		});	
	},
	navhtml:function(){
		var html='<div class="blockx-flat"><div class="header"><h3>设置栏目</h3>';
		html+='</div><div class="content"><form class="form-horizontal" role="form">';
		html+='<div class="form-group"><label class="col-sm-2 control-label">名称</label>';
		html+='<div class="col-sm-10"><input name="title" class="form-control" placeholder="名称">';
		html+='</div></div><div class="form-group"><label class="col-sm-2 control-label">类型</label>';
		html+='<div class="col-sm-10"><select class="form-control" name="type">';
		html+='<option value="click">发送关键字</option><option value="view">打开网页</option>';
		html+='<option value="scancode_push">扫码无提示</option><option value="scancode_waitmsg">扫码带提示</option>';
		html+='<option value="pic_sysphoto">拍照发图</option><option value="pic_photo_or_album">拍照或者相册发图</option>';
		html+='<option value="pic_weixin">微信相册发图</option><option value="location_select">发送位置</option>';
		html+='<option value="media_id">发送图文</option></select></div></div>';
		html+='<div class="form-group" id="keyword"><label class="col-sm-2 control-label">关键字</label>';
		html+='<div class="col-sm-10"><input name="key" class="form-control" placeholder="关键字"></div></div>';
		html+='<div class="content" id="news"><div class="tab-content no-padding"><div class="chat-wi"><div class="chat-tools">';
		html+='<ul class="nav nav-tabs"><li class="active"><div data-target="#appmsgArea" data-toggle="tab"><i class="fa fa-list-alt"></i>图文</div>';
		html+='</li><li><div data-target="#imageArea" data-toggle="tab"><i class="fa fa-picture-o"></i>图片</div>';
		html+='</li><li><div data-target="#audioArea" data-toggle="tab"><i class="fa fa-microphone"></i>语音</div>';
		html+='</li><li><div data-target="#videoArea" data-toggle="tab"><i class="fa fa-video-camera"></i>视频</div>';
		html+='</li></ul></div></div><div class="tab-pane active" id="appmsgArea">';
		html+='<div class="cl-mcont white height188"><div class="gallery-cont"></div></div>';
		html+='<div class="edit-up col-lg-12 upnews"><ul class="nav nav-pills">';
		html+='<li class="active opiframe" data-name="选择素材" url="/index.php?s=/Admin/Material/iframe/type/news/show/1.html"><i class="fa fa-cloud"></i>从素材库中选择</li>';
		html+='</ul></div></div><div class="tab-pane" id="imageArea"><div class="cl-mcont white height188">';
		html+='<div class="gallery-cont"></div></div><div class="edit-up col-lg-12 upimage">';
		html+='<ul class="nav nav-pills"><li class="active opiframe" data-name="选择素材" url="/index.php?s=/Admin/Material/iframe/type/image/show/1.html"><i class="fa fa-cloud"></i>从素材库中选择</li>';
		html+='</ul></div></div><div class="tab-pane" id="audioArea"><div class="cl-mcont white height188">';
		html+='<div class="gallery-cont"></div></div><div class="edit-up col-lg-12 upaudio"><ul class="nav nav-pills">';
		html+='<li class="active opiframe" data-name="选择素材" url="/index.php?s=/Admin/Material/iframe/type/voice.html"><i class="fa fa-cloud"></i>从素材库中选择</li>';
		html+='</ul></div></div><div class="tab-pane" id="videoArea"><div class="cl-mcont white height188">';
		html+='<div class="gallery-cont"></div></div><div class="edit-up col-lg-12 upvideo">';
		html+='<ul class="nav nav-pills"><li class="active opiframe" data-name="选择素材" url="/index.php?s=/Admin/Material/iframe/type/video.html"><i class="fa fa-cloud"></i>从素材库中选择</li></ul></div></div><input type="hidden" name="media_id" id="media_id"><div class="clearfix"></div></div></div>';
		html+='<div class="form-group" id="url"><label class="col-sm-2 control-label">url</label>';
		html+='<div class="col-sm-10"><input name="url" class="form-control" placeholder="url"></div></div>';
		html+='<div class="form-group"><div class="col-sm-offset-2 col-sm-10">';
		html+='<button type="button" class="btn btn-default">设 置</button></div>';
		html+='</div></form></div></div>';
		return html;
	},
	/*获取字符串长度*/
	getStrleng:function(str) {
		var myLen = 0;
		i = 0;
		for (; (i < str.length) && (myLen <= 60); i++) {
			if (str.charCodeAt(i) > 0 && str.charCodeAt(i) < 128)
			myLen++;
		else
			myLen += 2;
		}
		return myLen;
	}
};