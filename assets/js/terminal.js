/* Config */
window.TerminalConfig = {
    PromptText: '[<a id="username">'+window.username+'</a>@AirHost <a id="working_directory">'+window.working_directory+'</a>]#&nbsp;',
};
/* Start */
$(function() {
    // 布置PromptText
    $('.prompt').find('span').html(window.TerminalConfig.PromptText);
    // 布置History
    window.ClipboardHistory = {
        Data: [],
        Pos: -1,
    };
    // 点击Body自动聚焦Clipboard
    $('body').bind('click', function() { $('.clipboard').focus(); });
    // 框值变动判断增加同步数据
    $('.clipboard').bind('input propertychange', function () {
        var InputContent = $('#input-content');
        var Clipboard = $('.clipboard');
        var CursorBlink = $('.cursor-blink');
        var CursorBlinkAfter = $('#cursor-blink-after');
        // 长度
        var KuangL = Clipboard.val().length;
        var LeftL = HtmlDecode(String(InputContent.html())).length;
        var MiddleL = HtmlDecode(String(CursorBlink.html())).length;
        var RightL = HtmlDecode(String(CursorBlinkAfter.html())).length;
        // 判断
        if((LeftL !== KuangL)&&(MiddleL !== 0)&&(RightL !== 0)) {
            InputContent.html(HtmlEncode($(this).val().substring(0, KuangL-RightL-1)));
        }else if((LeftL !== KuangL)&&(MiddleL !== 0)&&(RightL == 0)) {
            InputContent.html(HtmlEncode($(this).val().substring(0, KuangL-1)));
        }else if((LeftL == KuangL)&&(MiddleL == 1)&&(RightL == 0)) {
            InputContent.html(HtmlEncode($(this).val().substring(0, KuangL-1)));
        }else{
            InputContent.html(HtmlEncode($(this).val()));
        }
        /*console.log('左'+LeftL+';中'+MiddleL+';右'+RightL+';框'+KuangL)*/
    });
    // 敲击键盘执行
    document.onkeydown = function(Event) {
        var oEvent = Event || window.event;
        var InputContent = $('#input-content');
        var Clipboard = $('.clipboard');
        var CursorBlink = $('.cursor-blink');
        var CursorBlinkAfter = $('#cursor-blink-after');
        var TerminalOutput = $('.terminal-output');
        // 敲击键盘左键
        if (oEvent.keyCode == 37) {
            CursorBlink.removeClass('empty');
            if(String(InputContent.html()).length !== 0) {
                if(Clipboard.val().length !== HtmlDecode(String(InputContent.html())).length) {
                    CursorBlinkAfter.prepend(HtmlEncode(HtmlDecode(String(CursorBlink.html()))));
                }
                CursorBlink.html(HtmlEncode(HtmlDecode(String(InputContent.html())).charAt(HtmlDecode(String(InputContent.html())).length - 1)));
                InputContent.html(HtmlEncode(HtmlDecode(String(InputContent.html())).substring(0, HtmlDecode(String(InputContent.html())).length - 1)));
            }
        }
        // 敲击键盘右键
        if (oEvent.keyCode == 39) {
            if(String(CursorBlinkAfter.html()).length !== 0) {
                if(Clipboard.val().length !== HtmlDecode(String(CursorBlinkAfter.html())).length) {
                    InputContent.append(HtmlEncode(HtmlDecode(String(CursorBlink.html()))));
                }
                CursorBlink.html(HtmlEncode(HtmlDecode(String(CursorBlinkAfter.html())).charAt(0)));
                CursorBlinkAfter.html(HtmlEncode(HtmlDecode(String(CursorBlinkAfter.html())).substring(1, HtmlDecode(String(CursorBlinkAfter.html())).length)));
            }else{
                InputContent.append(HtmlEncode(HtmlDecode(String(CursorBlink.html()))));
                CursorBlink.html('');
                CursorBlink.addClass('empty');
            }
        }
        // 敲击键盘上键
        if (oEvent.keyCode == 38) {
            if((window.ClipboardHistory.Pos !==window.ClipboardHistory.Data.length-1)&&(window.ClipboardHistory.Data.length!==0)) {
                window.ClipboardHistory.Pos = window.ClipboardHistory.Pos + 1;
                PutInputContent(String(window.ClipboardHistory.Data[window.ClipboardHistory.Pos]));
            }else{
                console.log('没有更多了');
            }
            return false;
        }
        // 敲击键盘下键
        if (oEvent.keyCode == 40) {
            if((window.ClipboardHistory.Pos > 0)&&(window.ClipboardHistory.Data.length!==0)) {
                window.ClipboardHistory.Pos = window.ClipboardHistory.Pos - 1;
                PutInputContent(String(window.ClipboardHistory.Data[window.ClipboardHistory.Pos]));
            }else if(window.ClipboardHistory.Pos == 0) {
                window.ClipboardHistory.Pos = window.ClipboardHistory.Pos - 1;
                CleanInputAllContent();
            }else{
                console.log('没有更多了');
            }
            return false;
        }
        // 敲击回车键提交输入内容
        if(oEvent.keyCode == 13){
            var ClipboardVal = $.trim(Clipboard.val());
            $('<div class="row"/>').html('<span>'+'['+window.username+'@AirHost '+window.working_directory+']#&nbsp;'+ClipboardVal+'</span>').appendTo(TerminalOutput);
            if(ClipboardVal !== '') {
                PressEnterAfterAction();
            }
            window.ClipboardHistory.Pos = -1; // 重置
            CleanInputAllContent();
            KeepBottomOfWindow();
            return false;
        }

        function PressEnterAfterAction() {
            var InputContent = $('#input-content');
            var Clipboard = $('.clipboard');
            var CursorBlink = $('.cursor-blink');
            var CursorBlinkAfter = $('#cursor-blink-after');
            // 增加历史记录
            if(Clipboard.val()!==window.ClipboardHistory.Data[0]) {
                window.ClipboardHistory.Data = [Clipboard.val()].concat(window.ClipboardHistory.Data);
            }
            $.ajax({
                type: "POST",
                url: "action.php",
                dataType: "json",
                data: {
                    'Command':Clipboard.val(),
                },
                success: function(json) {
                    if($.trim(json.Result)!=='') {
                        if (json.ReturnLevel !== undefined) {
                            AddOneRow(json.Result, json.ReturnLevel);
                        } else {
                            AddOneRow(json.Return);
                        }
                    }
                    if(json.Username != undefined){
                        $('#username').html(json.Username);
                        window.username = json.Username;
                    }
                    if(json.WorkingDirectory != undefined){
                        $('#working_directory').html(json.WorkingDirectory);
                        window.working_directory = json.WorkingDirectory;
                    }
                },
                error: function () {
                    AddOneRow('Sorry, Something Went Wrong ...','Error');
                }
            });
        }
    };
});

function HtmlEncode(str){
    str = str.replace(/&(?!#[0-9]+;|[a-zA-Z]+;)/g, '&amp;');
    return str.replace(/</g, '&lt;').replace(/>/g, '&gt;')
        .replace(/ /g, '&nbsp;');
}
function HtmlDecode(str){
    str = str.replace(/&amp;/g, '&');
    return str.replace(/&lt;/g, '<').replace(/&gt;/g, '>')
        .replace(/&nbsp;/g, ' ');
}
function CleanInputAllContent(){
    var InputContent = $('#input-content');
    var Clipboard = $('.clipboard');
    var CursorBlink = $('.cursor-blink');
    var CursorBlinkAfter = $('#cursor-blink-after');
    InputContent.html('');
    Clipboard.val('');
    CursorBlink.addClass('empty');
    CursorBlink.html('');
    CursorBlinkAfter.html('');
}
function PutInputContent(Str) {
    CleanInputAllContent();
    var Clipboard = $('.clipboard');
    var InputContent = $('#input-content');
    Clipboard.val(Str);
    InputContent.html(HtmlEncode(Str));
}
function KeepBottomOfWindow(){
    var WindowBody = $('body');
    WindowBody.scrollTop(WindowBody[0].scrollHeight);
}
function AddOneRow(Str,Level='Default'){
    if(Level=='Default'){
        $('<div class="row"/>').html('<span>'+Str+'</span>').appendTo($('.terminal-output'));
    }else if(Level=='Error'){
        $('<div class="row"/>').html('<span style="color:#ff4040;border-bottom: 1px dashed #ff4040">'+Str+'</span>').appendTo($('.terminal-output'));
    }
    KeepBottomOfWindow();
}