$(function() {
    window.SettingData = {
        BgColor:'',
        FontColor:'',
        FontSize:'middle',
        Directory:'/files/',
    };

    window.SettingData.BgColor = $('#BgColorSetting').find(".active").css("background-color");
    window.SettingData.FontColor = $('#FontColorSetting').find(".active").css("background-color");

    $(".main-container .header ul li").click(function (e) {

        if ($(this).hasClass('slider')) {
            return;
        }

        var whatTab = $(this).index();

        var howFar = 72 * whatTab;

        $(".slider").css({
            left: howFar + "px"
        });

        $(".ripple").remove();

        var posX = $(this).find('a').offset().left,
            posY = $(this).find('a').offset().top,
            buttonWidth = $(this).find('a').width(),
            buttonHeight = $(this).find('a').height();
        $(this).find('a').append("<span class='ripple'></span>");

        if (buttonWidth >= buttonHeight) {
            buttonHeight = buttonWidth;
        } else {
            buttonWidth = buttonHeight;
        }

        var x = e.pageX - posX - buttonWidth / 2;
        var y = e.pageY - posY - buttonHeight / 2;

        $(".ripple").css({
            width: buttonWidth,
            height: buttonHeight,
            top: y + 'px',
            left: x + 'px'
        }).addClass("ripple-effect");

    });

    $("button").click(function (e) {
        $(".ripple").remove();

        var posX = $(this).offset().left,
            posY = $(this).offset().top,
            buttonWidth = $(this).width(),
            buttonHeight = $(this).height();
        $(this).append("<span class='ripple'></span>");

        if (buttonWidth >= buttonHeight) {
            buttonHeight = buttonWidth;
        } else {
            buttonWidth = buttonHeight;
        }

        var x = e.pageX - posX - buttonWidth / 2;
        var y = e.pageY - posY - buttonHeight / 2;

        $(".ripple").css({
            width: buttonWidth,
            height: buttonHeight,
            top: y + 'px',
            left: x + 'px'
        }).addClass("ripple-effect");
    });

    document.onkeydown = function(Event) {
        var oEvent = Event || window.event;
        if(oEvent.keyCode == 13){
            Login();
            return false;
        }
    };

    $('#password').bind('input onpropertychange', function () {
        if($(this).hasClass('error')) {
            $(this).removeClass('error');
        }
    });
});

function ContentOpen(DivID) {
    var Content = $('.content');
    Content.children('div').hide();
    Content.find('#'+DivID).show();
}

function BgColorSetting(Obj){
    var BgColorSetting = $('#BgColorSetting');
    BgColorSetting.children('li').removeClass('active');
    Obj.addClass('active');
    window.SettingData.BgColor = Obj.css('background-color');
}

function FontColorSetting(Obj){
    var FontColorSetting = $('#FontColorSetting');
    FontColorSetting.children('li').removeClass('active');
    Obj.addClass('active');
    window.SettingData.FontColor = Obj.css('background-color');
}

function FontSizeSetting(Str,Obj){
    var FontSizeSetting = $('#FontSizeSetting');
    FontSizeSetting.children('li').removeClass('active');
    Obj.addClass('active');
    window.SettingData.FontSize = Str;
}


function Login(){
    var Username = $('#username');
    var Password = $('#password');
    if ($.trim(Username.val())==''){
        Username.focus();
        return false;
    }
    if ($.trim(Password.val())==''){
        Password.focus();
        return false;
    }
    $.ajax({
        type: "POST",
        url: "action-login.php",
        dataType: "json",
        data: {
            'username':$.trim(Username.val()),
            'password':$.trim(Password.val()),
            'setting':window.SettingData
        },
        success: function(json) {
            if(json.success){
                window.location.reload();
            }else{
                if(json.password_error){
                    Password.addClass('error');
                }else{
                    $('<span />').html(json.msg).appendTo('.form-msg').fadeOut(2000);
                }
            }
        },
        error: function () {
            $('<span />').html('请求错误').appendTo('.form-msg').fadeOut(2000);
        }
    });
}