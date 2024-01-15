/**
 * Created by 1040170 on 2018/11/29.
 */
<!-- 检查是否为手机装置 -->
if (/AppleWebKit.*Mobile/i.test(navigator.userAgent)
    || /Android/i.test(navigator.userAgent)
    || /BlackBerry/i.test(navigator.userAgent)
    || /IEMobile/i.test(navigator.userAgent)
    || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/
        .test(navigator.userAgent))) {
    var addr = window.location.href;
    if (addr.indexOf("mobi") >= 0) {

    } else if (sessionStorage['browseWay'] == 'mobile') {
        window.location.href = "/mobile/index.jsp" + window.location.search;
    } else if (sessionStorage['browseWay']) {
        var $el = $('<div style="position: fixed;width: 2.5em;height: 2.5em;z-index: 99999;background-color: #866546;color: #fff;line-height: 2.5em;text-align: center;font-size: 5em;border-radius: 50%;right: 0;bottom: 0;box-shadow: 1px 1px 10px #525151;">返回</div>');
        $el.click(function () {
            sessionStorage['browseWay'] = 'mobile';
            window.location.href = "/mobile/index.jsp"
                + window.location.search;
        });
        $('body').append($el);
    } else {
        window.location.href = "/mobile/index.jsp" + window.location.search;
    }
}

var fp_bbout_element_id = 'fpBB';
var io_bbout_element_id = 'ioBB';
var io_install_stm = false;
var io_exclude_stm = 12;
var io_install_flash = false;
var io_enable_rip = true;

function done(toFlashVars) {
    var attributes = {
        allowScriptAccess: "always"
    };
    swfobject.embedSWF("CpuCheck.swf", "core", "1", "1", "11.0.0",
        "playerProductInstall.swf", {
            "var1": toFlashVars,
            "var2": "http://device.168.tl"
        }, attributes);
}

$(function () {
    var addr = window.location.href;
    var NEEDMODIFY = '${session.NEEDMODIFY}';
    if (NEEDMODIFY == "1" && addr.indexOf("updatePassword") <= 0) {
        alert("[提示]您的密码安全指数较低，请修改以保障资金安全");
        window.location.href = "${ctx}/updatePassword.php";
        return;
    }
    var addr = window.location.href;
    var NEEDMODIFY = '${session.NEEDMODIFY}';
    if (NEEDMODIFY == "1") {
        alert("[提示]您的密码安全指数较低，请修改以保障资金安全");
        return;
    }

    // 处理帐号管理跳转
    var username = '${session.customer.loginname}';
    var role = '${session.customer.role}';
    var $setting = $('#J_welcome_box').find('a.J_setting');
    if (username && role == 'MONEY_CUSTOMER') {
        $setting.attr('href', '${ctx}/userManage.php')
    } else if (username && role != 'MONEY_CUSTOMER') {
        $setting.attr('href', '${ctx}/agentManage.php')
    }
});