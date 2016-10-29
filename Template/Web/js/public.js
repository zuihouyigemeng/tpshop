$(function() {
    $(".shareicon3").click(function() {
        $(".qecode-login").css("width", "390px");
    });
    // 关闭微信登录
    $(".back-home").click(function() {
        $(".qecode-login").css("width", "0px");
    });
    var random_bg = Math.floor(Math.random() * 3 + 1);
    var bg = 'url('+tplpath+'images/login' + random_bg + '.jpg)';
    $(".login-background").css("background-image", bg);
    if (random_bg == 1) {
        $(".login-back").css("background-color", "#F5AD00");
    } else if (random_bg == 2) {
        $(".login-back").css("background-color", "#61D2A7");
    } else {
        $(".login-back").css("background-color", "#55B0FF");
    }
    $(".user").click(function() {
        $(this).children("input").attr("placeholder", "邮箱地址");
    });

    $(".pwd").click(function() {
        $(this).children("input").attr("placeholder", "8-20位字母、数字或符号两种或以上组合");
    });

    $(".pwdok").click(function() {
        $(this).children("input").attr("placeholder", "再次输入密码");
    });

    $("input").blur(function(event) {
        $(this).attr("placeholder", "");
    });

    var verifyimg = $(".passcode").attr("src");
    $(".passcode").click(function() {
        if (verifyimg.indexOf('?') > 0) {
            $(".passcode").attr("src", verifyimg + '&random=' + Math.random());
        } else {
            $(".passcode").attr("src", verifyimg.replace(/\?.*$/, '') + '?' + Math.random());
        }
    });
})