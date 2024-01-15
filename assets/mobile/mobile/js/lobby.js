/**
 * Created by 1040170 on 2018/5/7.
 */

var _TEST_MODE = 0;
var loginName = $('#checkUserIsLoad').val();

//顶部返回键
$('#comm-back-button').show().attr("onclick", "window.location.href='/mobile/index.jsp'");
$('#comm-menu-button').hide();


/*真人体育*/

//mg真人
$(document).on('click', '#mgLive', function () {
    if (loginName == "") {
        alert('请登入后再进入游戏！');
        openLoginModule();
    } else {
        window.location.href = '/gameMGLive.php';
    }
});


/*捕鱼*/
$(document).on('click', "#agFish", function () {
    if (loginName == "") {
        alert("请先登入！");
        openLoginModule();
    } else {
        mobileManage.getLoader().open('进入中');
        mobileManage.ajax({
            url: 'mobi/gameAginRedirect.php?agH5Fish=1',
            callback: function (result) {
                mobileManage.getLoader().close();
                if (result.success) {
                    window.location.href = result.data.url;
                } else {
                    alert(result.message);
                }
            }
        });
    }
});

//捕鱼多福
$(document).on('click', "#skyFish", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        window.location.href = "/game/gameLoginPtSky.php?mode=real&gameCode=sw_fufish_intw&lobby=" + window.location.host + "/mobile/app/gameLobby.jsp";
    }
});

$(document).on('click', "#qlFish", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        window.location.href = "/game/gameLoginDTFish.php";
    }
});


//mw捕鱼
$(document).on('click', "#mwDown, #mwFish", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        mobileManage.getLoader().open('进入中');
        mobileManage.ajax({
            url: '/mobi/mwgLogin.php',
            callback: function (result) {
                mobileManage.getLoader().close();
                if (result.code == 0) {
                    window.location.href = result.data;
                } else {
                    alert(result.msg);
                }
            }
        });
    }
});


//ea
$(document).on('click', "#eaLive", function () {
    layer.open({
        title: ['EA手机版游戏'],
        skin: 'down-page',
        // area:['90%','auto'],
        type: 0,
        btn: ['确定'],
        content: '进入网页后，登入您的账号，即可游戏',
        yes: function () {
            webapp.openBrowser('http://phone.sunrise88.net/');
        }
    });
});

//ebet
$(document).on('click', "#ebetLive", function () {
    if (loginName == "") {
        alert('请登入后再进入游戏！');
        openLoginModule();
    } else {
        var url = 'http://qy.sdfd.rocks/h5/w96a9r?username={0}&accessToken={1}';
        mobileManage.getLoader().open("进入游戏中");
        mobileManage.ajax({
            url: 'mobi/getEbetToken.php',
            callback: function (result) {
                mobileManage.getLoader().close();

                if (result.success) {
                    if (!result.data) {
                        alert('进入游戏失败！');
                        return;
                    }
                    var link = String.format(url, result.data.loginname, result.data.accessToken);
                    window.location.href = link;
                } else {
                    alert(result.message);
                }
            }
        });
    }
});

//ag
$(document).on('click', "#agLive", function () {
    if (loginName == "") {
        alert('请先登入！')
        openLoginModule();
    }
    else {
        mobileManage.getLoader().open('进入中');
        mobileManage.ajax({
            url: 'mobi/gameAginRedirect.php',
            callback: function (result) {
                if (result.success) {
                    window.location.href = result.data.url;
                } else {
                    alert(result.message);
                }
                mobileManage.getLoader().close();
            }
        });
    }
});

//ag
$(document).on('click', "#agqjLive", function () {
    if (loginName == "") {
        alert('请先登入！')
        openLoginModule();
    } else {
        mobileManage.getLoader().open('进入中');
        var host = window.location.protocol + "//" + document.domain;

        $.post('/gameAgqjLogin.php?type=h5', {'url': host}, function (data) {

            if (data) {
                var url = eval("(" + data + ")");
                window.location.href = url.gameUrl;
            }

        });
    }
});
//BBIM
$(document).on('click', "#bbinLive", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        window.location.href = '/game/bbinMobiLogin.php';
    }
});

//sunbet
$(document).on('click', "#sunLive", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        window.location.href = '/game/sunbetLogin.php?type=MB';
    }
});

/*客户端下载*/
//PT老虎机
$(document).on('click', "#ptDown", function () {

    if (getMobileKind() == 'IOS') {
        layer.open({
            title: ['PT平台不支持苹果手机'],
            skin: 'down-page',
            // area:['90%','auto'],
            type: 0,
            btn: ['确定'],
            content: 'PT老虎机客户端不支持苹果手机，给您造成不便，敬请见谅！'
        });
    } else {
        layer.open({
            title: ['PT平台不支持苹果手机'],
            skin: 'down-page',
            area: ['90%', 'auto'],
            type: 0,
            btn: ['网页游戏', '下载客户端'],
            content: '1.尚未安装 "安卓PT老虎机客户端"玩家，请点击"继续下载",<br>2.已安装客户端玩家，则点击取消，由手机上客户端执行游戏即可<br>' +
            '3.手机客户端登入，账号前面请加上前缀QI,<br>4.网页端游戏请直接进入',
            yes: function () {
                window.location.href = '/mobile/app/gameLobby.jsp';
            },
            no: function () {
                window.location.href = 'https://support.qnappcb01.com/apk/pt.apk';
            }

        });
    }
});

//PT国际老虎机
$(document).on('click', "#ptaisaDown", function () {

    if (getMobileKind() == 'IOS') {
        layer.open({
            title: ['PT平台不支持苹果手机'],
            skin: 'down-page',
            // area:['90%','auto'],
            type: 0,
            btn: ['确定'],
            content: 'PT老虎机客户端不支持苹果手机，给您造成不便，敬请见谅！'
        });
    } else {
        layer.open({
            title: ['PT平台不支持苹果手机'],
            skin: 'down-page',
            area: ['90%', 'auto'],
            type: 0,
            btn: ['网页游戏', '下载客户端'],
            content: '1.尚未安装 "安卓PT老虎机客户端"玩家，请点击"继续下载",<br>2.已安装客户端玩家，则点击取消，由手机上客户端执行游戏即可<br>' +
            '3.手机客户端登入，账号前面请加上前缀Q,<br>4.网页端游戏请直接进入',
            yes: function () {
                window.location.href = '/mobile/app/gameLobby.jsp';
            },
            no: function () {
                window.location.href = 'https://support.qnappcb01.com/apk/mptClient.apk';
            }

        });
    }
});

//AG客户端
$(document).on('click', "#agDown", function () {
    layer.open({
        title: ['AGIN老虎机'],
        skin: 'down-page',
        area: ['90%', 'auto'],
        type: 0,
        btn: ['网页游戏', '下载客户端'],
        content: '客户端游戏方式：<br>1.首次登入手机客户端，请先登入Agin电脑网页-手机投注-进行扫码设置手势密码。<br>2.下载AGIN手机客户端。<br>3.登入账户前面加上qyqi_，接着输入您设置的密码即可。',
        yes: function () {
            mobileManage.getLoader().open('进入中');
            mobileManage.ajax({
                url: 'mobi/gameAginRedirect.php',
                callback: function (result) {
                    if (result.success) {
                        window.location.href = result.data.url;
                    } else {
                        model.$message.html(result.message);
                    }
                    mobileManage.getLoader().close();
                }
            });
        },
        no: function () {
            webapp.openBrowser('http://agmbet.com/');
        }

    });

});

//AG捕鱼客户端
$(document).on('click', "#agfishDown", function () {
    webapp.openBrowser('http://hunter2.agmjs.com/');
});

//eBETDown
$(document).on('click', "#ebetDown", function () {

    var downloadUrl = getMobileKind() == 'Android' ? 'https://www.ebetapp.com/applib/60/ebet.apk' : 'itms-services://?action=download-manifest&url=https://www.ebetapp.com/applib/60/ebet.plist';
    if (loginName == "") {
        alert('请登入后再进入游戏！');
    } else {
        window.location.href = downloadUrl;
    }
});


//DT
$(document).on('click', "#dtDown", function () {
    var downloadUrl = getMobileKind() == 'Android' ? 'http://down.dreamtech.asia/QY8/android.html' : 'http://down.dreamtech.asia/QY8/ios.html';
    layer.open({
        title: ['DT老虎机'],
        skin: 'down-page',
        // area:['90%','auto'],
        type: 0,
        btn: ['网页游戏', '下载客户端'],
        content: 'DT老虎机也有客户端啰，立即下载体验！',
        yes: function () {
            window.location.href = '/mobile/app/gameLobby.jsp?showType=DT';
        },
        no: function () {
            webapp.redirect(downloadUrl);
        }

    });
});


/*体育彩票*/

//沙巴体育
$(document).on('click', "#sbSport", function () {
    if (loginName == "") {
        alert('请登入后再进入游戏！');
        openLoginModule();
    } else {
        window.location.href = '/asp/sbMobileLogin.php';
    }
});

//iWin体育
$(document).on('click', "#xjSport", function () {
    if (loginName == "") {
        alert('请登入后再进入游戏！');
        openLoginModule();
    } else {
        var response = ajaxPost('/gameXJLogin.php?type=mobile');

        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                location.href = response.data
            } else {
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }
    }
});

//开元
$(document).on('click', "#kyqpSport", function () {

    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        window.location.href = '/game/nkyqpLogin.php?gameCode=0';
    }
});

//博乐
$(document).on('click', "#blSport", function () {

    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        var response = ajaxPost('/gameBLQPLogin.php?url=' + location.origin);

        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                location.href = response.data
            } else {
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }

    }
});

//博乐
$(document).on('click', "#hlSport", function () {

    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        var response = ajaxPost('/gameHLQPLogin.php?gameCode?url=' + location.origin);

        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                location.href = response.data
            } else {
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }

    }
});
//as
$(document).on('click', "#asSport", function () {

    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        var response = ajaxPost('/gameASLogin.php?url=' + location.origin);

        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                location.href = response.data
            } else {
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }

    }
});


//dt棋牌
$(document).on('click', "#dtSport", function () {
    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {
        DTLoginGame("");
    }
});

function DTLoginGame() {

    if (_TEST_MODE == 1) {

        return false;
    }


    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
        return false;
    } else {
        var chessUrl = '/gameDTChessLogin.php?playMode=0&lobby=1';
    }

    if (chessUrl != "") {

        layer.open({type: 2, content: '处理中'});

        var response = ajaxPost(chessUrl);

        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                formSubmit(response.data);
            } else {
                response = JSON.parse(response);
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }
    } else {
        alert("请重新再试!");
    }

}

function formSubmit(gameUrl) {

    $("#gameFormSubmit").remove();

    if (!gameUrl || gameUrl == "") {
        alert("请重新再试!");
        return false;
    }

    var postUrl = gameUrl;

    $('body').append('<form action="' + postUrl + '" method="post" id="gameFormSubmit"></form>');

    $("#gameFormSubmit").submit();

}


//761

$(document).on('click', "#qlySport,#qlyFish,#qlyFish1", function () {

    if (loginName == "") {
        alert('请先登入！');
        openLoginModule();
    } else {

        var response = ajaxPost('/game761Login.php?url='+location.origin);
        if (response) {

            layer.closeAll();

            if (response.code == 10000) {
                location.href = response.data
            } else {
                alert(response.message);
            }
        } else {
            alert("请重新再试!");
        }
    }
});


function ajaxPost(url, parm) {

    layer.open({type: 2, content: '处理中'});

    var RESULT;

    $.ajax({
        url: url,
        type: "post",
        dataType: "json",
        data: parm,
        async: false,
        success: function (jsonData) {

            layer.closeAll();

            RESULT = jsonData;
            return RESULT;
        }

    });

    return RESULT;
}


