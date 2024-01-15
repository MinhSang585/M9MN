/**
 * Created by 1040170 on 2019/1/31.
 */
$(window).load(function () {

    autoToNight();

    var checkUserIsLoad = $("#checkUserIsLoad").val();

    if (checkUserIsLoad) {
        getGuestbookCountNew();
    }

});

// 主账号和积分余额切换
$("#j-balance li").click(function () {
    var type = $(this).attr("data-type");

    if (type == "point") {
        $("#header_credit").hide();
        $("#header_friendPoint2").show();
        queryPoints();
    } else {
        $("#header_credit").show();
        $("#header_friendPoint2").hide();
    }
});

//清除缓存
$('.js-clear-cache').on('click', function () {

    layer.open({
        skin: 'tips-layer',
        closeBtn: false,
        content: '开始进行缓存清除，完毕后将会刷新网站！',
        btn: '确定',
        yes: function () {
            window.location.reload(true);
        }
    });

    $("body").addClass("layer-open");

});

//取得积分
function queryPoints() {

    $.post("/asp/queryPoints.php", function (returnedData) {


        $("#header_friendPoint").html(returnedData.nowPoint);
    });

}


//站内信
function getGuestbookCountNew() {
    $.post("/asp/getGuestbookCountNew.php", function (response) {

        if (response > 0) {
            $(".js-email-count").html(response);
        } else {
            $(".js-email-count").html("0");
        }
    });
}


//存取款款速转账快捷入口
$('.js-quick-enter').on('click', 'li', function () {

    var type = $(this).data('type');

    window.location.href = "/userManage.php?action=" + type;

});

//一般用户站内信快捷入口
$('.js-quick-email').on('click', function () {

    var type = $(this).data('type');

    window.location.href = "/userManage.php?action=" + type;
});


//点击切换
$('.js-change-mode').on('click', function () {
    var model = $(this).data('mode');

    if (model == "day") {
        model = "night";
    } else {
        model = "day";
    }

    changeDayOrNight(model);
    $(this).data('mode', model);
});

//自动切换
function autoToNight() {

    var fromCookie = getCookie("dayOrNight");
    var currentDate = new Date().getHours();

    if (!fromCookie || fromCookie == "") {
        // 晚上6点到凌晨0点 以及 凌晨0点到早上6点
        if (currentDate >= 18 && currentDate <= 24 || currentDate >= 0 && currentDate <= 6) {
            changeDayOrNight('night');
        }
        // 其馀时间默认为白天
        else {
            changeDayOrNight('day');
        }
    } else if (fromCookie == '1') {
        changeDayOrNight('night');
    } else if (fromCookie == '0') {
        changeDayOrNight('day');
    }
}

function changeDayOrNight(model) {

    var $btn = $('.js-change-mode');

    if (model == "night") {
        $('.header_top,.logo-cont,.top_nav,#newgong,.content-main-bg,.advantage,.footer-detail').addClass('night');

        $btn.data('mode', 'night');
        $btn.addClass('night');

        setCookie("dayOrNight", '1', 60);

    } else {
        $('.header_top,.logo-cont,.top_nav,#newgong,.content-main-bg,.advantage,.footer-detail').removeClass('night');

        $btn.data('mode', 'day');
        $btn.removeClass('night');

        setCookie("dayOrNight", '0', 60);
    }
}


//退出系统
function logout1() {
    openProgressBar();
    $.post("/asp/logout.php", {}, function (returnedData, status) {

        if ("success" == status) {
            delCookie();
            closeProgressBar();
            window.location.href = "/";
        } else {
            alert("登出失败");
        }
    }).fail(function () {
        window.location.href = "/";
    });
}


//
function _showLayer(msg, btn) {

    if (btn == "") {
        btn = '关闭';
    }

    layer.open({
        skin: 'tips22-layer',
        closeBtn: false,
        content: msg,
        btn: btn
    });

    $("body").addClass("layer-open");
}

//游戏展开和收起

$('.js-more-game').click(function () {

    var slotList =$(".slot-list-context");
    var   slotIcon = $('.slot-icon-cont');

    if(slotList.hasClass("active")){

        $(this).html('全部游戏<i class="iconfont icon-right-arr"></i>');
        slotList.removeClass('active');
        slotIcon.removeClass('active');


    }else{
        $(this).html('收起游戏<i class="iconfont icon-bottom-arr"></i>');
        slotList.addClass('active');
        slotIcon.addClass('active');

    }
});