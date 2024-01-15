/**
 * Created by 1040170 on 2019/1/8.
 */
//左侧快速转账收起
$('.js-open-modal').on('click', function () {
    var baseCont = $('.js-base-cont');
    baseCont.toggle();
    if (baseCont.css('display') == "block") {
        setTimeout("$('#transferGameOut').trigger('change');", 200);
    }
});
var loginName = $('#j-loginname').val();
//老虎机/真人/棋牌/彩票/体育/捕鱼公共使用方法

//获取转入游戏金额
function transferMoneyIn(gameCode) {
    if (loginName != "") {
        if (gameCode != "") {
            var $target = $("#transferMoneryInDiv");
            $target.html("<img src='/images/20121212661146573498.gif'>");

            $.post("/asp/getGameMoney.php", {"gameCode": gameCode}, function (returnedData, status) {
                if ("success" == status) {
                    $target.html(returnedData);
                }
            });
        }
    }

}

//获取转出游戏金额
function transferMoneyOut(gameCode) {
    if (loginName != "") {
        if (gameCode != "") {
            var $target = $("#transferMoneryOutDiv");
            $target.html("<img src='/images/20121212661146573498.gif'>");

            $.post("/asp/getGameMoney.php", {"gameCode": gameCode}, function (returnedData, status) {
                if ("success" == status) {
                    $target.html(returnedData);
                }
            });
        }
    }
}

//确认转账
//$('#j-transferAction').on('click', transferMoney);

//游戏转账
function transferMoney() {

    var transferGameOut = $("#transferGameOut").val();
    var transferGameIn = $("#transferGameIn").val();
    var transferGameMoney = $("#transferGameMoney").val();

    if (transferGameMoney == '') {
        _showLayer('请输入金额！', '确定')
    } else {
        openProgressBar();
        $.post("/asp/updateGameMoney.php", {
            "transferGameOut": transferGameOut,
            "transferGameIn": transferGameIn,
            "transferGameMoney": transferGameMoney
        }, function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();
                alert(returnedData);
                window.location.reload();
            }
        });
    }

}

//进入游戏
$('.js-join-game-btn').off('click').on('click', function () {

    var loginName = $('#j-login').val();
    var role = $('#role').val();
    var location = $('#location').val();


    //console.log(loginName);
    if (!loginName) {
        layer.open({
            content: '您好，请先登入！',
            btn: ['去登入'],
            skin: 'tips-layer',
            yes: function () {
                openLoginModule();
            }
        });
    } else {

        if (role === "MONEY_CUSTOMER") {
            var type = $(this).data('type');

            switch (type) {

                case"aglive":
                    window.location.href = location+ '/gameAginRedirect.php';
                    break;
                case"agqjlive":
                    agqjJoinGame();
                    break;
                case"bbinlive":
                    window.location.href = location+' /game/bbinLogin.php?gameKind=live';
                    break;
                case"sblive":
                    window.location.href = location+'/game/sunbetLogin.php';
                    break;
                case"mglive":
                    window.location.href = location+'/gameMGLive.php';
                    break;
                case"ebetlive":
                    window.location.href = location+'/gameEbetLoginNew.php';
                    break;
                case"ealive":
                    window.location.href = location+ 'http://live.sunrise88.net';
                    break;
                case"ag":
                    window.location.href = location+'/gameAginBuyuRedirect.php';
                    break;
                case"sw":
                    window.location.href = location+'/game/gameLoginPtSky.php?mode=real&gameCode=sw_fufish_intw&lobby=' + window.location.host + '/gamePt.php';
                    break;
                case"sb":
                    window.location.href = location+'/sport.jsp';
                    break;

                case"xj": window.location.href = location +'/qyty.jsp';

                    break;
            }


            //188
            // function xjJoinGame() {
            //
            //     openProgressBar();
            //
            //     $.post('/gameXJLogin.php?type=web', function (reData) {
            //
            //
            //         if (reData.code === "10000") {
            //
            //             window.location.href = reData.data;
            //
            //         } else {
            //             _showLayer(reData.message);
            //         }
            //         closeProgressBar();
            //     });
            // }


            //ag旗舰
            function agqjJoinGame() {
                var host = window.location.protocol + "//" + document.domain;
                $.post('/gameAgqjLogin.php?type=flash', {'url': host}, function (data) {

                    if (data) {
                        var url = eval("(" + data + ")");
                        window.location.href = url.gameUrl;
                    }

                });
            }

        }
    }

});



//判断最新优惠跳转到那种优惠
$(function () {
    goToPromotionType();
});

function goToPromotionType() {
    var pathName = window.location.pathname;
    var type = '';

    switch (pathName) {
        case"/live.jsp":
            type = 2;
            break;
        case"/chess.jsp":
            type = 3;
            break;
        case"/lotteryGame.jsp":
            type = 4;
            break;
        case"/sportGame.jsp":
            type = 5;
            break;
        case"/fish.jsp":
            type = 6;
            break;
        default:
            type = 1;
            break;
    }
    $('.js-promotion-navbar-list').find('a').attr('href', '/promotion.jsp?type=' + type);
}


