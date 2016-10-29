(function($) {
    $.fn.player = function(settings) {
        var config = {
            progressbarWidth: '200px',
            progressbarHeight: '5px',
            progressbarColor: '#22ccff',
            progressbarBGColor: '#eeeeee',
            defaultVolume: 0.8
        };

        if (settings) {
            $.extend(config, settings);
        }

        var playControl = '<i class="fa fa-play"></i>';
        var stopControl = '<i class="fa fa-pause"></i>';

        this.each(function() {
            $(this).before('<div class="simple-player-container">');
            $(this).after('</div>');
            $(this).parent().find('.simple-player-container').prepend(
                '<div><ul>' + 
                    '<li style="display: inline-block; padding: 0 5px;"><a style="text-decoration: none;"' +
                    ' class="start-button" href="javascript:void(0)">' + playControl + '</a></li>' + 
                    '<li class="progressbar-wrapper" style="display: inline-block; cursor: pointer; width:' + config.progressbarWidth + ';">' + 
                        '<span style="display: block; background-color: ' + config.progressbarBGColor + '; width: 100%; ">' + 
                        '<span class="progressbar" style="display: block; background-color: ' + config.progressbarColor +
                                                         '; height: ' + config.progressbarHeight + '; width: 0%; ">' +
                        '</span></span>' + 
                    '</li>' + 
                '</ul></div>'
            );

            var simplePlayer = $(this).get(0);
            var button = $(this).parent().find('.start-button');
            var progressbarWrapper = $(this).parent().find('.progressbar-wrapper');
            var progressbar = $(this).parent().find('.progressbar');

            simplePlayer.volume = config.defaultVolume;

            button.click(function() {
                if (simplePlayer.paused) {
                    /*stop all playing songs*/
                    $.each($('audio'), function () {
    					this.pause();
						$(this).parent().find('.fa-pause').addClass('fa-play').removeClass('fa-pause');
					});
                    simplePlayer.play();
                    $(this).find('.fa-play').addClass('fa-pause').removeClass('fa-play');
                } else {
                    simplePlayer.pause();
                    $(this).find('.fa-pause').addClass('fa-play').removeClass('fa-pause');
                }
            });

            progressbarWrapper.click(function(e) {
                if (simplePlayer.duration != 0) {
                    left = $(this).offset().left;
                    offset = e.pageX - left;
                    percent = offset / progressbarWrapper.width();
                    duration_seek = percent * simplePlayer.duration;
                    simplePlayer.currentTime = duration_seek;
                }
            });


            $(simplePlayer).bind('ended', function(evt) {
                simplePlayer.pause();
                button.find('.fa-pause').addClass('fa-play').removeClass('fa-pause');
                progressbar.css('width', '0%');
            });

            $(simplePlayer).bind('timeupdate', function(e) {
                duration = this.duration;
                time = this.currentTime;
                fraction = time / duration;
                percent = fraction * 100;
                if (percent) progressbar.css('width', percent + '%');
            });

            if (simplePlayer.duration > 0) {
                $(this).parent().css('display', 'inline-block');
            }

            if ($(this).attr('autoplay') == 'autoplay') {
                button.click();
            }
        });

        return this;
    };
})(jQuery);
