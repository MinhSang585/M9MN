var header = $('.common-header');
var footer = $('.common-footer');

$(function () {

    //开启关闭
    $('body>.common-screen').click(function () {
        if ($('body').hasClass('show-contact')) {
            $('body').removeClass('show-contact');
        }
        if ($('body').hasClass('show-nav')) {
            $('body').removeClass('show-nav');
        }
    });

    //开启关闭 選單
    header.find('#comm-menu-button').click(function () {
        if ($('body').hasClass('show-nav')) {
            $('body').removeClass('show-nav');
        } else {
            $('body').addClass('show-nav');
        }
    });

    //开启关闭 联系我们
    header.find('#comm-other-button').click(function () {
        if ($('body').hasClass('show-contact')) {
            $('body').removeClass('show-contact');
        } else {
            $('body').addClass('show-contact');
        }
    });

    //开启关闭 联系我们
    $('.comm-other-button').click(function () {
        if ($('body').hasClass('show-nav')) {
            $('body').removeClass('show-nav');
        }
        if ($('body').hasClass('show-contact')) {
            $('body').removeClass('show-contact');
        } else {
            $('body').addClass('show-contact');
        }
    });
});


//手机输入的时候隐藏footer
function inputFocusEvent() {
    $('input').focus(function () {
        if (!$(this).attr('readonly')) {
            header.css('top', '-100%');
            footer.css('top', '100%');
        }
    });
    $('input').focusout(function () {
        header.css('top', '0');
        footer.css('top', 'auto');
    });
}

//电话回播
function makeCall() {
    mobileManage.getModel().open('makeCall');
    // otherButtonClick();
}

//开启email
function openEmail() {
    //safri 无法用window.open开启
    webapp.redirect('mailto:cs@qy8cs.vip');
}


//登出
function logout(manage) {
    manage.getLoader().open('登出中');
    manage.getUserManage().logout(function (data) {
        if (data.success) {
            manage.redirect('index');
        } else {
            alert(data.message);
            manage.getLoader().close();
        }
    });
}

//取得積分
function queryPoints(isLogin) {
    if (isLogin) {
        $.post("/asp/queryPoints.php", function (returnedData) {
            var nowPoint = parseFloat(returnedData.nowPoint);
            $(".friendPoint").html(nowPoint);
        });
    }
}

function transferMoneyNav(target, gameCode) {

    var $allTarget = $(".queryAccountBalence");
    var $show = $(".balence-show");
    $show.hide();

    if (gameCode != "") {
        $.post("/asp/getGameMoney.php", {"gameCode": gameCode}, function (returnedData, status) {
            if ("success" == status) {
                var value = returnedData;
                var account = $(target).find("option:selected").text();

                var display = $.trim(value);
                display = display.split("元")[0];
                display = account + ":¥" + display;

                $show.text(display).show();
                $allTarget.val("");
            }
        });
    }

}

function commBackBtnShow() {
    $("#comm-menu-button").hide();
    $("#comm-back-button").show();
}
