/**
 * Created by 1040170 on 2018/9/3.
 */
$(function () {
    mainCredit();
});

//查询主账户余额
function mainCredit() {
    $.post('/asp/getGameMoney.php', {gameCode: "self"}, function (data) {

        $('.js-credit').html(data);
    })
}

//快速转账
$('#transferGameOut').change(function () {

    var type = $(this).val();
    var selfHtml =
        '<option value="dtchess">DT棋牌账户</option>' +
        '<option value="blqp">博乐棋牌账户</option>' +
        '<option value="as">AS真人棋牌账户</option>' +
        '<option value="hlqp">欢乐棋牌账户</option>' +
        '<option value="nkyqp">开元棋牌账户</option>';


    var otherHtml = '<option value="self">iWin账户</option>';

    $('#transferGameIn').empty();

    if (type === "self") {
        $('#transferGameIn').append(selfHtml);
        transferMoneyIn("dtchess");
    } else {
        $('#transferGameIn').append(otherHtml);
        transferMoneyIn("self");
    }
});

var _TEST_MODE = 0;

$(function () {
    chessGameInit();
});

$(window).load(function () {

    $(document).on('click', '.js-chess-btn', joinChessGame);
    $(document).on('click', '.js-chess-demo-btn', joinChessDemoGame);

    $('.js-list-items').on('click', 'dd', chessTypeChange);

    clickTab();
});


$('#j-transferAction').click(function () {
    transferMoney();
});


//游戏初始化
function chessGameInit() {

    $.post('/data/chess/chess.json?v=777', function (data) {

        var chessHtml = '';

        for (var i = data.length; i--;) {


            if (data[i].type === "DT CHESS") {
                chessHtml += '<div class="game-info box">' +
                    '<div class="game-pic">' +
                    ' <img class="lazy" data-original="/images/chessgames/' + data[i].pic + '"   src="/images/chessgames/' + data[i].pic + '" >' +
                    ' </div>' +
                    ' <div class="name chess-title"> ' +
                    '<h4>' + data[i].type + '·' + data[i].name + '</sub></h4> ' +
                    '</div> ' +
                    '<div class="game-brief"> ' +
                    '<div class="btn-wp text-center">' +
                    ' <a class="js-chess-demo-btn chess-btn" href="javascript:;" data-id="' + data[i].id + '" data-type="' + data[i].type + '" >免费试玩</a> ' +
                    ' <a class="js-chess-btn chess-btn" href="javascript:;" data-id="' + data[i].id + '" data-type="' + data[i].type + '" data-tag="' + data[i].tag + '" >进入游戏</a> ' +
                    '</div>' +
                    ' </div>' +
                    ' </div>';
            } else {
                chessHtml += '<div class="game-info box">' +
                    '<div class="game-pic">' +
                    ' <img class="lazy" data-original="/images/chessgames/' + data[i].pic + '"   src="/images/chessgames/' + data[i].pic + '" >' +
                    ' </div>' +
                    ' <div class="name chess-title"> ' +
                    '<h4>' + data[i].type + '·' + data[i].name + '</sub></h4> ' +
                    '</div> ' +
                    '<div class="game-brief"> ' +
                    '<div class="btn-wp text-center">' +
                    ' <a class="js-chess-btn chess-btn" href="javascript:;" data-id="' + data[i].id + '" data-type="' + data[i].type + '" ' +
                    'data-tag="' + data[i].tag + '" >进入游戏</a> ' +
                    '</div>' +
                    ' </div>' +
                    ' </div>';
            }


        }
        $('#chessAll').html(chessHtml);

    });

}

//游戏分类
function chessTypeChange() {

    var type = $(this).data('type');

    console.log(type)
    $(this).addClass('active').siblings().removeClass('active');

    $('.game-info .js-chess-btn').each(function (index, item) {

        if ($(item).data('type') == type || (type == '' && $(item).data('tag') == 'hot')) {
            $(item).parents('.game-info').show();
        } else {
            $(item).parents('#chessAll .game-info').hide();
        }
    })

}

//免费游戏
function joinChessDemoGame() {

    if ($('#j-loginname').val() != '') {

        var chessId = $(this).data('id'),
            chessType = $(this).data('type'),
            chessUrl = '';

        switch (chessType) {
            case'DT CHESS':
                chessUrl = '/gameDTChessLogin.php?gameCode=' + chessId + '&playMode=1';
                break;
        }


        if (_TEST_MODE == 1) {
            console.log(chessUrl);
            return false;
        }

        openProgressBar();

        var response = ajaxPost(chessUrl);

        closeProgressBar();

        if (response.code == 10000) {
            formSubmit(response.data);
        } else {
            response = JSON.parse(response);
            alert(response.message);
        }

    } else {
        layer.open({
            skin: 'top-class',
            closeBtn: false,
            content: '请登入后再进入游戏！',
            btn: ['确定'],
            yes: function () {
                openLoginModule();
            }
        });

        $("body").addClass("layer-open");
    }

}

//进入游戏
function joinChessGame() {

    if ($('#j-loginname').val() != '') {

        var chessId = $(this).data('id'),
            chessType = $(this).data('type'),
            chessUrl = '',
            credit = $('#j-credit').val(),
            platFrom = '',
            platFromName = '';

        switch (chessType) {
            case'KAI YUAN':
                chessUrl = "/game/nkyqpLogin.php?gameCode=" + chessId;
                platFrom = 'nkyqp';
                platFromName = '开元棋牌';
                break;
            case'DT CHESS':
                chessUrl = '/gameDTChessLogin.php?gameCode=' + chessId + '&playMode=0';
                platFrom = 'dtchess';
                platFromName = 'DT棋牌';
                break;
            case'BOLE':
                chessUrl = '/gameBLQPLogin.php?gameCode=' + chessId + '&url=' + location.origin;
                platFrom = 'blqp';
                platFromName = '博乐棋牌';
                break;
            case'AS':
                chessUrl = '/gameASLogin.php?gameType=' + chessId + '&url=' + location.origin;
                platFrom = 'as';
                platFromName = 'AS真人棋牌';
                break;
            case'HUAN LE':
                chessUrl = '/gameHLQPLogin.php?gameCode=' + chessId + '&url=' + location.origin;
                platFrom = 'hlqp';
                platFromName = '欢乐棋牌';
                break;
        }

        if (_TEST_MODE == 1) {

            return false;
        }

        $.post('/asp/getGameMoney.php', {gameCode: platFrom}, function (data) {


            if (data) {
                var money = data.split('元')[0];


                $('.js-des-account').html(platFromName);
                $('.js-des-money').html(data);

                if (parseFloat(money) < 5) {
                    $('.js-transfer-title').html('您的' + platFromName + '账户已<span class="money">不足5元</span> ，是否快速转帐？');

                    if (parseFloat(credit) < 1) {

                        layer.open({
                            skin: 'tips-layer less-money-layer',
                            title: false,
                            content: '<img src="/images/tips.png" alt="" id="tip"><div id="tip-cont"><h3><i class="iconfont icon-taoxin"></i>温馨提示：</h3>您的主账户已<span class="money">不足1元</span>，<br>是否点击下方按钮去充值？</div>',
                            area: ['525px', ''],
                            btn: ['快速充值', '进入游戏'],
                            yes: function () {
                                window.location.href = "/userManage.php?action=1"
                            },
                            btn2: function () {
                                joinChessGame(chessType, chessUrl);
                            }
                        });
                    } else {
                        layer.open({
                            type: 1,
                            skin: 'transfer-layer tips-layer',
                            title: '快速转账',
                            content: $('#fast-deposit-cont'),
                            area: ['500px', ''],
                            btn: ['快速转账', '进入游戏'],
                            yes: function () {
                                fastTranferMethod(chessType, platFrom, chessUrl);
                            },
                            btn2: function () {
                                joinChessGame(chessType, chessUrl);
                            }
                        });
                    }

                } else {
                    joinChessGame(chessType, chessUrl);
                }
            }
        });


        //正常转账
        function fastTranferMethod(chessType, platFrom, chessUrl) {


            var transferGameMoney = $('#transfer-money').val();

            if (transferGameMoney != '') {

                openProgressBar();
                layer.closeAll();
                $.post("/asp/updateGameMoney.php", {
                    "transferGameOut": 'self',
                    "transferGameIn": platFrom,
                    "transferGameMoney": transferGameMoney
                }, function (returnedData, status) {
                    if ("success" == status) {
                        closeProgressBar();
                        if (returnedData === "转账成功！") {
                            mainCredit();
                            layer.closeAll();
                            joinChessGame(chessType, chessUrl);
                        } else {
                            alert(returnedData);
                        }
                    }
                });
            } else {
                alert('请输入转账金额！')
            }

        }

        //进入游戏区分dt及其他棋牌
        function joinChessGame(chessType, chessUrl) {
            if (chessType == 'DT CHESS') {
                var response = ajaxPost(chessUrl);

                if (response != "") {
                    if (response.code == 10000) {
                        formSubmit(response.data);
                    } else {
                        alert(response);
                    }
                }

            } else if (chessType == 'BOLE') {
                var response1 = ajaxPost(chessUrl);

                if (response1 != "") {

                    if (response1.code == 10000) {
                        closeProgressBar();
                        window.open(response1.data);
                    } else {
                        alert(response1.message);
                    }
                }
            } else if (chessType == 'AS') {
                var response2 = ajaxPost(chessUrl);

                if (response2 != "") {

                    if (response2.code == 10000) {
                        closeProgressBar();
                        window.open(response2.data);
                    } else {
                        alert(response2.message);
                    }
                }
            } else if (chessType == 'HUAN LE') {
                var response3 = ajaxPost(chessUrl);

                if (response3 != "") {

                    if (response3.code == 10000) {
                        closeProgressBar();
                        window.open(response3.data);
                    } else {
                        alert(response3.message);
                    }
                }
            } else if (chessType == '761CITY') {
                var response4 = ajaxPost(chessUrl);

                if (response4 != "") {

                    if (response4.code == 10000) {
                        closeProgressBar();
                        window.open(response4.data);
                    } else {
                        alert(response4.message);
                    }
                }
            } else {
                location.href = chessUrl;
            }
        }
    } else {
        layer.open({
            skin: 'tips-layer',
            closeBtn: false,
            content: '请登入后再进入游戏！',
            btn: ['确定'],
            yes: function () {
                openLoginModule();
            }
        });

        $("body").addClass("layer-open");
    }

}


function formSubmit(gameUrl) {

    $("#dtGameFormSubmit").remove();

    if (!gameUrl || gameUrl == "") {
        alert("请重新再试!");
        return false;
    }

    var postUrl = gameUrl

    $('body').append('<form action="' + postUrl + '" method="post" id="dtGameFormSubmit"></form>');

    $("#dtGameFormSubmit").submit();

}


//快速转账
$(function () {
    $('.js-chess-navbar-list').addClass('active').siblings('.active').removeClass('active');
    $('.logo-cont').attr('href', '/index.jsp');

});


$(function () {
    goToPromotionType();
});

function goToPromotionType() {
    $('.js-promotion-navbar-list').find('a').attr('href', '/promotion.jsp?type=3');
}

//导航对应棋牌点击
function clickTab() {

    var search = location.search;
    if (search != "") {
        var search1 = search.split('=')[1];
        var platFromName = '';
        switch (search1) {
            case'kyqp':
                platFromName = 'KAI YUAN';
                break;
            case'dtchess':
                platFromName = 'DT CHESS';
                break;
            case'blqp':
                platFromName = 'BOLE';
                break;
            case'as':
                platFromName = 'AS';
                break;
            case'hlqp':
                platFromName = 'HUAN LE';
                break;
        }



        setTimeout(function () {
            $('.js-list-items dd[data-type="' + platFromName + '"]').trigger('click');
        }, 500)


    } else {
        setTimeout(function () {
            $('.js-list-items dd:nth-of-type(1)').trigger('click');
        }, 500)
    }

}