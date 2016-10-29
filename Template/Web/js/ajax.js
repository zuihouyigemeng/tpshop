(function() {

//ajax get请求

    $(document).on('click','.ajax-get',function(){
        var target;
        var that = this;
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            var index = layer.msg('加载中', {icon: 16,shade:0.8});
            $.get(target).success(function(data){
                layer.close(index);
                if (data.status == 1) { 
                    layer.msg(data.info, {icon: 1,shade:0.8},function(){
                        var callback = $(that).attr('callback');
                        if(callback){
                            eval(callback);
                        }else{
                           location.href = data.url; 
                        }
                    });
                } else {
                    layer.msg(data.info, {icon: 2,shade:0.8});
                }
            });

        }
        return false;
    });
    $('.ajax-post').click(function() {
        var target, query, form;
        var target_form = $(this).attr('target-form');
        var that = this;
        if (($(this).attr('type') == 'submit') || (target = $(this).attr('href')) || (target = $(this).attr('url'))) {
            form = $('.' + target_form);
            if (form.get(0).nodeName == 'FORM') {
                target = form.get(0).action;
                query = form.serialize();
            } else {
                query = form.find('input,select,textarea').serialize();
            }
            var index = layer.msg('加载中', {icon: 16,shade:0.8});
            $(that).attr('autocomplete', 'off').prop('disabled', true);
            $.post(target, query).success(function(data) {
                layer.close(index);
                if (data.status == 1) {
                    layer.msg(data.info, {icon: 1,shade:0.8},function(){
                        var callback = $(that).attr('callback');
                        if(callback){
                            eval(callback);
                        }else{
                           location.href = data.url; 
                        }
                    });
                } else {
                    layer.msg(data.info, {icon: 2,shade:0.8},function(){
                        if(data.url){
                            location.href = data.url;
                        }else{
                           $(that).prop('disabled', false); 
                        }
                    });
                }
            });
        }
        return false;
    });
})();