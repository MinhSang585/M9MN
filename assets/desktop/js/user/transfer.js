/**
 * Created by 1040170 on 2018/7/11.
 */

//显示各平台余额
$(function () {
    $('.all-account-list .item a').click(function () {
        var $transferGameOut = $('#transferGameOut');//来源账户
        var $transferGameIn = $('#transferGameIn');//目标账户
        var $parent = $(this).parents('.item');

        if ($parent.attr('id') == 'transferGameIn') {//目标账户
            if ($(this).attr('data-id') != 'self') {

                $transferGameOut.val('self')
            }
        }
        if ($parent.attr('id') == 'transferGameOut') {//来源账户
            if ($(this).attr('data-id') != 'self') {
                $transferGameIn.val('self')
            }
        }

        $(this).addClass('active').siblings().removeClass('active')
            .parent('.clear').siblings().children().removeClass('active');


        var $span = $(this).find('span');
        $span.html('加载中...');
        var gameCode = $(this).data('id');

        $.post("/asp/getGameMoney.php", {
            "gameCode": gameCode
        }, function (returnedData, status) {
            if ("success" == status) {
                returnedData === '尚未登入！请重新登入！' && (returnedData = '请重新登入');
                $span.html(returnedData);
            }
        });
    })
});

//标签切换
$(document).ready(function () {

    $('.js-main-plat-transfer').on('click', 'li', function () {

        $(this).addClass('active').siblings().removeClass('active');

        $('.item.tab').eq($(this).index()).addClass('tab-active').siblings().removeClass('tab-active');

        if ($(this).index() == 2) {
            signPrize();
        } else {
            refreshBalance();
        }


    })

});

//刷新显示余额
$('.js-refresh-balance1').click(function () {
    liId = $('.js-main-plat-transfer').find('li.active').data('id');
    if (liId == 'qd') {

        signPrize();
    } else {
        refreshBalance1();
    }

});

//签到余额
function signPrize() {

    $(".transfer-total").html('正在刷新..');

    $.post('/asp/getGameMoney.php', {gameCode: 'qd'}, function (data) {

        $(".transfer-total").html(data);
    });

}

//刷新余额
function refreshBalance1() {

    $.ajax({
        type: "post",
        url: "/asp/refreshUserBalance.php",
        cache: false,
        beforeSend: function () {
            $(".transfer-total").html("正在刷新..");
        },
        success: function (data) {
            if ($.isNumeric(data)) {
                $('.transfer-total').html(data);
            }
        },
        error: function () {
            alert("服务繁忙");
        }
    });
}

//游戏转账
function transferMonery() {

    var transferAccountIndex = $('.js-main-plat-transfer li.active').index();
    var transferGameMoney = $("#transferGameMoney").val();
    var transferGameOut = '';
    var transferGameIn = '';

    if (transferAccountIndex == 0) {

        transferGameIn = $(".item.tab.tab-active a.active").data('id');
        transferGameOut = 'self';

    } else if (transferAccountIndex == 2) {

        transferGameIn = $(".item.tab.tab-active a.active").data('id');
        transferGameOut = 'qd';

    } else if (transferAccountIndex == 1) {

        transferGameIn = 'self';
        transferGameOut = $(".item.tab.tab-active a.active").data('id');
    }


    if (transferGameMoney == "") {
        alert("转账金额不能为空！");
        return false;
    }
    if (isNaN(transferGameMoney)) {
        alert("转账金额只能是数字!");
        return false;
    }else{

        // 必须从self本帐户发动转入或者转出，或者由qd签到余额发动转出
        openProgressBar();

        $('.deposit-btn-class').attr('disabled','disabled');

        $.post("/asp/updateGameMoney.php", {
            "transferGameOut": transferGameOut,
            "transferGameIn": transferGameIn,
            "transferGameMoney": transferGameMoney
        }, function (returnedData, status) {
            if ("success" == status) {

                $('.deposit-btn-class').removeAttr('disabled');

                transferMoneryOut(transferGameOut);
                transferMoneryIn(transferGameIn);
                closeProgressBar();
                alert(returnedData);
            }
        });
    }


}


