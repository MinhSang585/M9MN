/**
 * Created by Alex.Hu on 2019/11/12.
 */
$(window).load(function () {
    initAgentQrcode();  //动态生成二维码
});

//代理合作
$(document).on("click", "#agent-link", function () {
    window.location.href="agent.html";
});

//返回顶部
$(document).on("click", "#cs-goTop", function () {
    $("html,body").animate({scrollTop: 0}, 1200);
});


//动态生成二维码
function initAgentQrcode() {

    setAgentQrcode();

//        $.post('/data/getAppVersionCustomInfo/both.json', function (data) {
    $.post('/app/getAppVersionCustomInfo.php', function (data) {
        try {
            if (data.length > 0) {
                if ('${session.customer==null}' == 'true') {
                    $(".Qrcode").append("<div class='mask'><p>请登入账号</p></div>");
                }
            } else {
//                    setAppQrcode();
            }

//                $(".qy-code-down").removeClass("hide").addClass("animated fadeInRight");
        } catch (e) {
            console.log(e)
        }

    });

    function setAgentQrcode() {

        var loginname = '${session.customer.loginname}';

        try {

            $.post("/thirdPartyPay/downloadPackage.php", function (response) {

                var url = "";

                if (response != "" && response.code == "10000") {

                    if (response.data.randomData) {
                        var randomData = response.data.randomData;
                        url = 'https://support.qnappcb01.com/APP/web/app-qy-go.html' + '#https://' + window.location.host + '/pakage.jsp?loginname=' + loginname + '&randomData=' + randomData;
                    } else {
                        url = 'https://support.qnappcb01.com/APP/web/app-qy-go.html' + '#https://' + window.location.host + '/pakage.jsp';
                    }
                } else {
                    url = 'https://support.qnappcb01.com/APP/web/app-qy-go.html' + '#https://' + window.location.host + '/pakage.jsp';
                }



                $(".Qrcode").qrcode({
                    render: "canvas", // 渲染方式有table方式和canvas方式
                    width: 120,   //默认宽度
                    height: 120, //默认高度
                    text: url, //二维码内容
//                        typeNumber: -1,   //计算模式一般默认为-1
                    correctLevel: 0 //二维码纠错级别
                });

//                    注册成功
                $(".reg-Qrcode").qrcode({
                    render: "canvas", // 渲染方式有table方式和canvas方式
                    width: 171,   //默认宽度
                    height: 171, //默认高度
                    text: url, //二维码内容
//                        typeNumber: -1,   //计算模式一般默认为-1
                    correctLevel: 0 //二维码纠错级别
                });
            });

        } catch (e) {

        }


        $(".j-agent-hide").hide();

    }

    function setAppQrcode() {
        $(".Qrcode").html('<img class="code" src="/images/downcenter/qy-client-down5.png" width="120">');
    }

}




$(window).load(function () {
    var isLogin = $("#j-loginName").val();

    if (isLogin) {
        //getHeaderBookCount();
    }

    var isDomain = document.location.pathname;

    // 如果在存款页面
    if (isLogin && isDomain.indexOf("userManage") > -1) {
        getHeaderQueryPoints();
    }

});


//邮件数量
function getHeaderBookCount() {

    if (getCookie(COOKIE_ITEM["getGuestbookCountNew"])) {
        var response = getCookie(COOKIE_ITEM["getGuestbookCountNew"]);


        $(".js-email-count").html(response);
    } else {

        $.post("/asp/getGuestbookCountNew.php", function (response) {
            if (response > 0) {
                $(".js-email-count").html(response);
                setCookie(COOKIE_ITEM["getGuestbookCountNew"], response);
            }
        });
    }

}

// 取得积分
function getHeaderQueryPoints() {

    var data = getCookie(COOKIE_ITEM["queryPoints"]);

    if (data) {
        var returnedData = JSON.parse(data);
    } else {
        var returnedData = ajaxPost("/asp/queryPoints.php");
        setCookie(COOKIE_ITEM["queryPoints"], JSON.stringify(returnedData));
    }

    setHeaderQueryPoints(returnedData);

    // 配置积分
    function setHeaderQueryPoints(returnedData) {
        if (returnedData) {
            var _point = returnedData.nowPoint;
            $("#header_friendPoint").html("");
            $("#header_friendPoint").html(_point);
        }
    }
}

//刷新积分
function refreshBalance(obj) {
    $.ajax({
        type: "post",
        url: "${ctx}/asp/refreshUserBalance.php",
        cache: false,
        beforeSend: function () {
            $(".j-balance").html("正在刷新..");
        },
        success: function (data) {
            if ($.isNumeric(data)) {
                $('.j-balance').text(data);
            }
        },
        error: function () {
            alert("服务繁忙");
        }
    });
}



$(window).load(function () {
    var opt = {
        animate: {
            duration: 5000,
            easing: 'linear',
            step: function () {
                $(this).text(Math.floor(this.countNum))
            },
            complete: function () {
                $(this).text(this.countNum)
            }
        },
        timeRange: {min: 20, max: 90},
        changePeriod: 60000
    };

    function changeTime() {
        var randomSec = opt.timeRange.min + Math.floor(Math.random() * (opt.timeRange.max - opt.timeRange.min + 1))
        var minute = Math.floor(randomSec / 60);
        var second = randomSec % 60;
        $('.minute').animate({countNum: minute}, opt.animate);
        $('.second').animate({countNum: second}, opt.animate);
    }

    changeTime();
    setInterval(changeTime, opt.changePeriod);

    var optwo = {
        animate: {
            duration: 5000, //
            easing: 'linear',
            step: function () {
                $(this).text(Math.floor(this.countNum))
            },
            complete: function () {
                $(this).text(this.countNum)
            }
        },
        timeRange: {min: 180, max: 600}, //
        changePeriod: 60000 //
    }

    function changeTimeTwo() {
        var randomSec = optwo.timeRange.min + Math.floor(Math.random() * (optwo.timeRange.max - optwo.timeRange.min + 1));
        var minute = Math.floor(randomSec / 60);
        var second = randomSec % 60;
        $('.minutetwo').animate({countNum: minute}, optwo.animate);
        $('.secondtwo').animate({countNum: second}, optwo.animate);
    }

    changeTimeTwo();
    setInterval(changeTimeTwo, optwo.changePeriod);

});