(function( $ ){
    // 当domReady的时候开始初始化
    $(function() {
        var $list=$('#fileList'),$img,thumbnailWidth=640,thumbnailHeight=320,crop,jcrop_api;
        var uploader = WebUploader.create({
            auto: true,
            swf: 'dist/Uploader.swf',
            server: '/Picture/uploadHeadPicture',
            pick: '#filePicker',
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            compress: {
                width: 1600,
                height: 1600,
                quality: 90,
                allowMagnify: false,
                crop: false,
                preserveHeaders: true,
                noCompressIfLarger: false,
                compressSize: 0
            },
            disableGlobalDnd: true,
            fileSizeLimit: 50 * 1024 * 1024,    // 200 M
            fileSingleSizeLimit: 5 * 1024 * 1024    // 50 M
        });

        uploader.on('ready', function() {
            window.uploader = uploader;
        });

        // 当有文件添加进来的时候
        uploader.on( 'fileQueued', function( file ) {
            var $li = $(
                    '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                    '</div>'
                    ),
                $img = $li.find('img');
            $list.html( $li );
            uploader.makeThumb( file, function( error, src ) {
                if ( error ) {
                    $img.replaceWith('<span>不能预览</span>');
                    return;
                }
                $img.attr( 'src', src );
            }, thumbnailWidth, thumbnailHeight );
            $img.attr('id',"cropbox");
        });
        // 文件上传过程中创建进度条实时显示。
        uploader.on( 'uploadProgress', function( file, percentage ) {
            var $li = $( '#'+file.id ),
                $percent = $li.find('.progress span');
            // 避免重复创建
            if ( !$percent.length ) {
                $percent = $('<p class="progress"><span></span></p>')
                        .appendTo( $li )
                        .find('span');
            }
            $percent.css( 'width', percentage * 100 + '%' );
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file, response ) {
            uploadheads=true;
            $( '#'+file.id ).addClass('upload-state-done').removeClass('thumbnail');
            $('#filePicker').append('<div class="webuploader-pick btn-upload">保存头像</div>');
            $('#cropbox').attr( 'src', response['path']+'?random='+Math.random());
            $('#cropbox').Jcrop({
                aspectRatio: 0,
                boxWidth : thumbnailWidth,     //画布宽度
                boxHeight : thumbnailHeight,    //画布高度
                onSelect: updateCoordinate,
                minSize: [160,160],
                setSelect: [0,0,160,160]
            },function(){
                jcrop_api=this;
                crop=this.tellScaled();
            });
        });
        // 文件上传失败，显示上传出错。
        uploader.on( 'uploadError', function( file ) {
            var $li = $( '#'+file.id ),
                $error = $li.find('div.error');

            // 避免重复创建
            if ( !$error.length ) {
                $error = $('<div class="error"></div>').appendTo( $li );
            }

            $error.text('上传失败');
        });
        // 完成上传完了，成功或者失败，先删除进度条。
        uploader.on( 'uploadComplete', function( file ) {
            $( '#'+file.id ).find('.progress').remove();
            uploader.reset();
        });

        function updateCoordinate(c) {
            crop = c;
        }
        $('#uploader-demo').on('click','.btn-upload',function(){
            //检查是否已经裁剪过
            var btnthis=this;
            if (crop.w == undefined || crop.w == 0) {
                layer.alert('请先选出图片中需要的部分',{ icon: 0,});
                return;
            }
            $(this).text('正在保存');
            $(this).addClass('disabled');
            var crop2 = crop.x + ',' + crop.y + ',' + crop.w  + ',' + crop.h;
            $.post('/picture/cropImg/', {crop: crop2}, function (a) {
                if (a.status) {
                    $(btnthis).text('保存头像');
                    $(btnthis).removeClass('disabled');
                    $('.webuploader-pick.btn-upload').remove();
                    $('.user-face img').attr('src',a.path);
                    $list.empty();
                    jcrop_api.destroy();
                    layer.msg(a.info);
                    $(".user-face-show").hide();
                } else {
                    layer.alert(a.info, {icon: 0});
                    //恢复按钮
                    $(btnthis).text('保存头像');
                    $(btnthis).removeClass('disabled');
                }
            });

        })

    });

})( jQuery );