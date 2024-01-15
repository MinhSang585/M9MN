$(function () {

    prizeAdd();


    $('#arrow_prev').click(function () {
        var $item = $('#items-menu li');
        var ind = $('#items .on').index();
        var size = $('#items .item').size() - 1;
        if (ind === 0) {
            $item.eq(size).trigger("click");
        } else {
            $item.eq(ind - 1).trigger("click");
        }
    });

    $('#arrow_next').click(function () {
        var $item = $('#items-menu li');
        var ind = $('#items .on').index();
        var size = $('#items .item').size() - 1;
        if (ind === size) {
            $item.eq(0).trigger("click");
        } else {
            $item.eq(ind + 1).trigger("click");
        }
    });
});


//领取关闭按钮
function prizeAdd() {
    $(".float-close").click(function () {
        var target = $(this).data('num');
        $(".float-icon" + target).fadeOut();
    });
}

$(window).load(function () {
    var loginname = $("#j-loginName").val();
    getFirstLoginStatus(loginname);

});

var SESSION_STORAGE = ["first", "firstlogin", "loginstate"];

function initSessionStorage() {
    for (var i = 0; i < SESSION_STORAGE.length; i++) {
        if (window.sessionStorage) {
            var key = SESSION_STORAGE[i];
            if (!sessionStorage.getItem(key)) {
                sessionStorage.setItem(key, false);
            }
        }
    }
}

function getFirstLoginStatus(loginname) {
    if (window.sessionStorage) {
        initSessionStorage();
        if (loginname) {
            /* do something */
        } else {

            // 没登入
            if (sessionStorage.getItem("first") != 'true') {
                openActivityModal();
                sessionStorage.setItem('first', 'true');
            }
        }
    }
}

function openActivityModal() {
    $.post("/asp/checkConfigSystem.php", {"typeNo": "type003", "itemNo": "001"}, function (msg) {

        if (msg) {

            var bgImg = '/images/activity-modal9.png';

            $('<img/>').attr('src', bgImg).load(function () {
                $(this).remove();
                $("#modal-activity .huodong-box").css('background', 'url(' + bgImg + ') no-repeat center');

                var strs = msg.split('#');
                var str1 = strs[0];
                var str2 = strs[1];
                var str3 = strs[2];
                $('#nr').html(str2);
                $('#h3').html(str1);
                $('#ts').html(str3);
                $('#modal-activity').modal('show');
            });
        }
    });
}