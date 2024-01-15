$(function () {
    setTab();


    // menu();

    /* 体验金 */
    $(document).on('click', '.ptebox .ptgame', function () {
        alert('请下载手机app申请！');
    });

});


function checkPhoneCode(phoneCode) {
    var flag = false;
    $.ajax({
        type: "post",
        url: "/asp/checkPhoneCode.php",
        data: "code=" + phoneCode,
        async: false,
        success: function (data) {
            if (!(data == 'right')) {
                alert(data);
            } else {
                flag = true;
            }
        }
    });
    return flag;
}


function repeatBankCards() {
    var flag = false;
    $.ajax({
        type: "post",
        url: "/asp/repeatBankCards.php",
        async: false,
        success: function (data) {
            if (!(data == 'success')) {
                if ((confirm(data) == true && (data == '请绑定银行卡'))) {
                    window.location.href = "/userManage.php";
                }
            } else {
                flag = true;
            }

        }
    });
    return flag;
}


$(window).load(function () {

    var isLogin = $("#checkUserIsLoad").val();
    var isDomain = document.location.pathname;

    // 如果在页面
    if (isLogin && isDomain.indexOf("userManage") > -1) {
        // queryfriendMoney();
        initUserQueryPoints();
    }
});


function isBlindingCard() {
    var flag = false;
    $.ajax({
        type: "post",
        url: "/asp/isBlindingCard.php",
        async: false,
        success: function (data) {
            if (!(data == 'pass')) {
                alert(data);
                $('.main .link .m_out').trigger("click");
                $('#box4 .item .lock').trigger("click");
                window.location.reload();

            } else {
                alert('m');
                flag = true;
            }
        },
        fail: function () {
            window.location.reload(true);
        }
    });
    return flag;
}

//显示游戏金额
function transferMoneryShow() {
    var transferGameOut = $("#transferGameOut").val();
    var transferGameIn = $("#transferGameIn").val();
    transferMoneryOut(transferGameOut);
    transferMoneryIn(transferGameIn);
}

//获取游戏金额
function transferMoneryIn(gameCode) {
    if (gameCode != "") {

        var $target = $("#transferMoneryInDiv");

        $target.html("<img src='/images/20121212661146573498.gif'></img>");

        $.post("/asp/getGameMoney.php", {
                "gameCode": gameCode
            },
            function (returnedData, status) {
                if ("success" == status) {
                    $target.html(returnedData);
                }
            });
    }
}

//获取游戏金额
function transferMoneryOut(gameCode) {

    if (gameCode != "") {

        var $target = $("#transferMoneryOutDiv");

        $target.html("<img src='/images/20121212661146573498.gif'></img>");

        $.post("/asp/getGameMoney.php", {
                "gameCode": gameCode
            },
            function (returnedData, status) {
                if ("success" == status) {
                    $target.html(returnedData);
                }
            });
    }
}

//获取游戏金额
function transferMoneryOutBalance(gameCode) {
    if (gameCode != "") {

        var $target = $("#transferMoneryOutDiv1");

        $target.html("<img src='/images/20121212661146573498.gif'></img>");

        $.post("/asp/getGameMoney.php", {
                "gameCode": gameCode
            },
            function (returnedData, status) {
                if ("success" == status) {
                    $target.html(returnedData);
                }
            });
    }
}

function setTab() {
    $(".setTabGroup").each(function (i, domEle) {
        var _menu = $(domEle).find(".setTabMenu");
        var _con = $(domEle).find(".setTabCon");
        _menu.each(function (j, domEle2) {
            $(domEle2).click(function () {
                _menu.removeClass("this");
                $(this).addClass("this");
                _con.hide().eq(j).show();
            });
        });
    });
}

function setWindowPos(boxElm) {
    var wintop = 0,
        winleft = 0,
        _left = 0,
        _top = 0;
    wintop = $(window).scrollTop();
    winleft = $(window).scrollLeft();

    if (!$("#screen")) {
        $("body").append('<div id="screen"></div>');
    }
    $('html,body').css({
        'overflow': 'hidden'
    });
    $('#screen').css({
        'width': $(document).width(),
        'height': $(document).height()
    }).show();

    _left = winleft + $(window).width() / 2 - 363;
    _top = wintop + $(window).height() / 2 - 300;

    $(boxElm).css({
        'left': _left,
        'top': _top,
    }).addClass("bounceIn").show();
}

$(window).on('resize',
    function () {
        var wintop = $(window).scrollTop();
        var winleft = $(window).scrollLeft();
        $('#screen').css({
            'width': $(document).width(),
            'height': $(document).height()
        });
        $('#box3,#box2,#box66,#box1,#boxfz,#boxjj').css({
            'left': winleft + $(window).width() / 2 - 363,
            'top': wintop + $(window).height() / 2 - 300
        });
    });

function refreshBalance(obj) {
    $.ajax({
        type: "post",
        url: "/asp/refreshUserBalance.php",
        cache: false,
        beforeSend: function () {
            $(".j-balance").text("正在刷新..");
            $(".transfer-total").text("正在刷新..");
        },
        success: function (data) {
            if ($.isNumeric(data)) {
                $('.j-balance').text(data);
                $('.transfer-total').text(data);
            }
        },
        error: function () {
            alert("服务繁忙");
        }
    });
}


function menu() {
    $('#ul-menu li').click(function () {
        var ind = $(this).index();
        if (!$(this).hasClass('active')) {
            $(this).addClass('active').siblings().removeClass('active');
        }
        $('#mainright .item-info').eq(ind).addClass('active').siblings().removeClass('active');
    });
}

// 初始化 积分余额
function initUserQueryPoints() {
    var data = getCookie(COOKIE_ITEM["queryPoints"]);

    if (data) {
        var returnedData = data;
    } else {
        var returnedData = ajaxPost("/asp/queryPoints.php", "");
        setCookie(COOKIE_ITEM["queryPoints"], returnedData);
    }

    setUserQueryPoints(returnedData);
}

// 設置 积分余额
function setUserQueryPoints(returnedData) {

    if (returnedData) {
        var oldPoint = returnedData.oldPoint;
        var nowPoint = returnedData.nowPoint;
        var ratio = returnedData.ratio;
        var bonus = returnedData.bonus;

        $("#friendPoint, #totalfriendPoint, #moneypoint").html("");

        $("#friendPoint").html(nowPoint);
        $("#totalfriendPoint").html(oldPoint);
        $("#moneypoint").html(bonus + "元");
        $("#friendPoint1").html(nowPoint);
    }
}

// 查詢 积分余额
function queryPoint() {
    $.post("/asp/queryPoints.php", function (returnedData, status) {
        if ("success" == status) {
            setUserQueryPoints(returnedData);
        }
    }).fail(function () {
        window.location.reload(true);
    });
    return false;
}


//初始化好友邀请
function queryfriendMoney() {


    if (getCookie(COOKIE_ITEM["queryfriendBonue"])) {
        var returnedData = getCookie(COOKIE_ITEM["queryfriendBonue"]);
        setFrindCom(returnedData);
    } else {

        openProgressBar();

        $.post("/asp/queryfriendBonue.php", function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();
                setFrindCom(returnedData);
                setCookie(COOKIE_ITEM["queryfriendBonue"], returnedData);
            }
        });
    }

    function setFrindCom(returnedData) {

        var strs = returnedData.split('#');
        $("#friendmoney").html("");
        $("#friendmoney").html(strs[0] + "元");

        var friendUrl = "https://" + document.domain + "?friendcode=" + strs[1];
        $("#friendurl1, #friendurl0").val(friendUrl);

        $('#qrcode').html('');

        $("#qrcode").qrcode({
            render: "canvas", // 渲染方式有table方式和canvas方式
            text: friendUrl,//二维码内容
            width: 200,
            height: 200,
            correctLevel: 0 //二维码纠错级别
        });

    }

    return false;
}

//复制功能
function copyText(num) {
    var input = document.getElementById("friendurl" + num);
    input.select();
    document.execCommand("copy");
    alert("复制成功");
}


//查询奖金
function querySignAmount() {
    openProgressBar();
    $.ajax({
        url: "/asp/querySignAmount.php",
        type: "post",
        dataType: "text",
        data: "",
        success: function (msg) {
            closeProgressBar();
            $("#qdmoney1").html("");
            $("#qdmoney1").html(msg + "元");
        }
    });
}

//催账记录
function urgeOrderRecord(pageIndex) {
    if (pageIndex <= 1) {
        pageIndex = 1;
    }
    openProgressBar();
    $.post("/asp/queryUrgeOrderPage.php", {
            "pageIndex": pageIndex,
            "size": 5
        },
        function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();
                $("#czDiv").html("");
                $("#czDiv").html(returnedData);
            }
        });
    return false;
}

//负盈利反赠记录
function losePromoRecord() {
    //选择老虎机窗口共用，区分周周回馈|负盈利反赠
    $(".j-hd-url").val("lp");

    openProgressBar();
    $.post("/asp/queryPTLosePromoReccords.php", {
            "pageIndex": 1,
            "size": 8
        },
        function (returnedData, status) {
            if ("success" == status) {
                closeProgressBar();
                $("#losepromoRecordDiv").html("");
                $("#losepromoRecordDiv").html(returnedData);
            }
        });
    return false;
}



















