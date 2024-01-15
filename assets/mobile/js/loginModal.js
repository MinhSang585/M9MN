/**
 * Created by 1040170 on 2018/8/3.
 */


//判断客户端
var checkClientos = checkClientos();
//判断浏览器
var checkBrowser = checkBrowser();

var _clientos = checkClientos;
var _browser = checkBrowser;

//登入
function openLoginModule() {
    var html = '<div class="layer-wrapper">' +
        '<div>' +
        '<div class="input-group">' +
        '<label for="usernameLogin">账  号：</label>' +
        '<input id="usernameLogin" type="text" placeholder="输入账号" />' +
        '</div>' +
        '<div class="input-group">' +
        '<label for="pwdLogin">密  码：</label>' +
        '<input id="pwdLogin" type="password"  placeholder="输入密码" autocomplete = "new-password"/>' +
        '</div>' +
        '<div class="input-group" id="game-input-group2" style="display: none;">' +
        '<label for="codeModel" style="display: inline-block">验证码：</label>' +
        '<input id="codeModel" type="text" placeholder="输入验证码" />' +
        ' <img id="imgTryCode">' +
        '</div>' +
        '</div>' +
        '</div>';

    layer.open({
        title: ['登入'],
        skin: 'tips-layer',
        className: 'tips-layer',
        type: 0,
        area: ['400px', ''],
        btnAlign: 'c',
        btn: '登入',
        content: html,
        success: function () {
            refreshValidateCode();
        },
        yes: function () {

            var loginname = $("#usernameLogin").val();
            if (!loginname) {
                alert("账号不能为空！");
                return false;
            }
            var password = $("#pwdLogin").val();
            if (!password) {
                alert("密码不能为空！");
                return false;
            }
            var code = $("#codeModel").val();
            var data = {};

            if ($('#game-input-group2').css('display') == 'block') {
                data = {
                    "clientos": _clientos,
                    "browser": _browser,
                    "loginname": loginname,
                    "password": password,
                    "imageCode": code,
                    "loginType": 1
                };
            } else {
                data = {
                    "clientos": _clientos,
                    "browser": _browser,
                    "loginname": loginname,
                    "password": password,
                    "loginType": 1
                };
            }


            $.post("/asp/newLogin.php", data, function (returnedData, status) {
                if ("success" == status) {
                    if (returnedData == "showValidateVode") {
                        alert('帳號或密码错误!', '确定');
                        $('#game-input-group2').show();
                        refreshValidateCode();
                    } else if (returnedData == "SUCCESS") {

                        if (loginname.substr(0, 2) == "a_") {
                            window.location.reload();
                        } else {
                            sessionStorage.setItem('firstlogin', true);
                            sessionStorage.setItem('loginstate', true);
                            window.location.reload(true);
                        }

                    } else {

                        alert(returnedData);
                        var str2 = '已被锁';
                        if (returnedData.indexOf(str2) > -1) {
                            window.location = "../forgotAccount.jsp?type=0";
                        }
                    }

                }
            });
        }
    })
}

$(document).on('change', '#usernameLogin', function () {

    var loginname = $("#usernameLogin").val();
    if (!loginname) {
        _showLayer('账号不能为空！', '确定');
        return false;
    } else {
        $.post('/asp/getLoginWrongTime.php', {loginname: loginname}, function (data) {
            if (data > 1) {
                $('#game-input-group2').show();
            } else {
                $('#game-input-group2').hide();
            }
        })
    }
});


//退出系统
function logout() {
    openProgressBar();
    $.post("/asp/logout.php", {}, function (returnedData, status) {
        if ("success" == status) {
            delCookie();
            window.location.href = "/";
        } else {
            alert("登出失败");
        }
    }).fail(function () {
        window.location.href = "/";
    });
}
