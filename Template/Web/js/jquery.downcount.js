(function ($) {
    $.fn.downCount = function (options, callback) {
        var settings = $.extend({
                difference: null,
                now:null
            }, options);
        var container = this;
        var finish = parseInt(settings.difference)+parseInt(settings.now);
        var fix=new Date().getTime()-parseInt(settings.now);
        function countdown () {
            var difference=finish-(new Date().getTime()-fix);
            if (difference < 0) {
                clearInterval(interval);
                if (callback && typeof callback === 'function') callback();
                return;
            }
            var s = parseInt(difference / 1000 / 60 / 60 % 60),
            o = parseInt(difference / 1000 / 60 % 60),
            u = parseInt(difference / 1000 % 60),
            a = parseInt(difference % 1000);
            container.html('<span class="countDiv"><span class="position">'+updateDuo(o)+'</span><span class="position">'+updateDuo(u)+'</span><span class="position">'+updateDuo(a)+'</span></span>');
        };
        function updateDuo(value){
            return '<span>'+Math.floor(value/10)%10+'</span><span>'+value%10+'</span>';
        }
        var interval = setInterval(countdown, 40);
    };
})(jQuery);