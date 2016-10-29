(function( $ ){
    // 当domReady的时候开始初始化
    $(function() {

        var $wrap = $('#uploader'),
            $queue = $('<ul class="filelist"></ul>').appendTo($wrap.find( '.queueList' )),
            $statusBar = $wrap.find( '.statusBar' ),
            $info = $statusBar.find( '.info' ),
            $upload = $wrap.find( '.uploadBtn' ),
            $placeHolder = $wrap.find( '.placeholder' ),
            $progress = $statusBar.find( '.progress' ).hide(),
            fileCount = 0,
            successNum = 0,
            localIds=new Array;

        $.get("/user/wxshare/").success(function(status){
            if(status.status){
                wx.config(status.info);
            }
        });
        $('.webuploader-pick').click(function() {
            wx.chooseImage({
                sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
                sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
                success: function (res) {
                    $.each(res.localIds, function(i, value) {
                        localIds.push(value);
                        addFile(i,value);
                    });
                }
            });
            $upload.removeClass('disabled');
        });

        // 当有文件添加进来时执行，负责view的创建
        function addFile(id,src){
            fileCount++;
            if ( fileCount === 1 ) {
                $placeHolder.addClass( 'element-invisible' );
                $statusBar.show();
            }
            if ( fileCount >9 ) {
                layer.open({content: '请不要超过9张图片!'});
                return false;
            }
            var $li = $( '<li id="file_'+id+'">' +
                '<p class="imgWrap"></p>'+
                '</li>'),
            $btns = $('<div class="file-panel">' +
                '<span class="cancel">删除</span></div>').appendTo( $li ),
            $prgress = $li.find('p.progress span'),
            $wrap = $li.find( 'p.imgWrap' ),
            img = $('<img src="'+src+'">');
            $wrap.empty().append( img );
            text = '选中' + fileCount + '张图片';
            $info.html( text );
            $li.on( 'mouseenter', function() {
                $btns.stop().animate({height: 30});
            });

            $li.on( 'mouseleave', function() {
                $btns.stop().animate({height: 0});
            });
            $btns.on( 'click', 'span', function() {
                removeFile( id );
            });
            $li.appendTo($queue);
        }

        $upload.on('click', function(){
            if ($(this).hasClass('disabled')){
                return false;
            }
            uploadWx();
        });

        function removeFile( id ) {
            fileCount--;
            var $li = $('#file_'+id);
            localIds.splice(id,1);
            $li.off().find('.file-panel').off().end().remove();
        }

        function uploadWx(){
            var num=0;
            $.each(localIds, function(i, value) {
                num=i;
                wx.uploadImage({
                    localId: value, // 需要上传的图片的本地ID，由chooseImage接口获得
                    isShowProgressTips: 1, // 默认为1，显示进度提示
                    success: function(res){
                        $.post('/Picture/uploadWx',{media_id:res.serverId}).success(function(data){
                            $('[id^="file_"]').not(':has(input)').eq(0).append('<span class="success"></span>'+
                            '<input name="pic[]" type="hidden" value="'+data.path+'" />'+
                            '<input name="thumbpic[]" type="hidden" value="'+data.thumbpath+'" />');
                            successNum++;
                            text = '共' + fileCount + '张，已上传' + successNum + '张';
                            $info.html( text );
                        });
                    }
                }) 
            })
            localIds.splice(0,num+1);
            $upload.addClass('disabled');
        }
    });
})( jQuery );