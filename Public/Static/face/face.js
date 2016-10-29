;!function(){

	var gather = {
		//简易编辑器
		layEditor: function(options){
			var html = '<div class="fly-edit">'
							+'<span type="face" title="插入表情"><i class="fa fa-smile-o"></i>表情</span>'
					   +'</div>';
			var log = {}, mod = {
				face: function(editor, self){ //插入表情
					var str = '', ul, face = gather.faces;
					for(var key in face){
						str += '<li title="'+ key +'"><img src="'+ face[key] +'"></li>';
					}
					str = '<ul id="LAY-editface" class="layui-clear">'+ str +'</ul>';
					layer.tips(str, self, {
						tips: 3
						,time: 0
						,skin: 'layui-edit-face'
					});
					$(document).on('click', function(){
						layer.closeAll('tips');
					});
					$('#LAY-editface li').on('click', function(){
						var title = $(this).attr('title') + ' ';
						gather.focusInsert(editor[0], title);
					});
					event.stopPropagation ? event.stopPropagation() : event.cancelBubble = true;
				}
			};
			layer.ready(function(){
				gather.faces = face;
				$(options.elem).each(function(index){
					var that = this, othis = $(that), parent = othis.parent();
					parent.append(html);
					parent.find('.fly-edit span').on('click', function(){
						mod[$(this).attr('type')].call(that, othis, this);
					});
				});
			});
		}
	   
		,escape: function(html){
			return String(html||'').replace(/&(?!#?[a-zA-Z0-9]+;)/g, '&amp;')
			.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/'/g, '&#39;').replace(/"/g, '&quot;');
		}
		
		,focusInsert: function(obj, str){
			var result, val = obj.value;
			obj.focus();
			if(document.selection){ //ie
				result = document.selection.createRange(); 
				document.selection.empty(); 
				result.text = str; 
			} else {
			   result = [
					val.substring(0, obj.selectionStart),
					str,
					val.substr(obj.selectionEnd)
				];
				obj.focus();
				obj.value = result.join('');
			}
		}
	
		//内容转义
		,content: function(content){
			content = gather.escape(content||'') //XSS
			.replace(/\[([^\s\[\]]+?)\]/g, function(face){  //转义表情
				return '<img alt="'+ face +'" title="'+ face +'" src="' + gather.faces[face] + '">';
			})
			.replace(/\n/g, '<br>') //转义换行   
			return content;
		}
	}
	
	//加载编辑器
	gather.layEditor({
		elem: '#LAY_desc'
	});

}();