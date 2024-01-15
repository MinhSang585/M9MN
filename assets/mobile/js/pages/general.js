
var loading_layer;


$(function () {
    //const loginBtn = document.getElementById('loginBtn');
    //const close_login = document.getElementById('close_login');
    //const modal_login = document.getElementById('modal_login');

    //loginBtn.addEventListener('click', () => {
    //    modal_login.classList.add('show-mobileModal');
    //});

    //close_login.addEventListener('click', () =>
    //    modal_login.classList.remove('show-mobileModal')
    //  );

    var hostname = window.location.hostname;
    if (is_accessBlog == "1") {
        $(".blogitem").show();
    }

    $('#loginBtn').click(function() {
        $('#modal_login').addClass('show-mobileModal');
        $('#form_login .username').focus();

        $('.footer_beforelogin').hide();
    });
    $('#close_login').click(function () {
        $('#modal_login').removeClass('show-mobileModal');
        $('.footer_beforelogin').show();
    });

    $('.menuMainMobile .menu_text').click(function () {
        if ($('#isLogedin').val() == "false") {
            $('.menuMainMobile .closeBtn').click();
            $('#loginBtn').click();
        }
    });
});


showLoading = function () {
    //alert('asd');
    loading_layer = layer.open({ type: 2 });
}

closeLoading = function () {
    layer.close(loading_layer);
}

alertMSG = function (msg) {
    var index = layer.open({
        content: msg
        , btn: 'OK'
    });
}

alertMSGRes = function (msg, redirect) {
    var index = layer.open({
        content: msg
        , btn: 'OK'
        , yes: function () {
            layer.close(index);
            if (redirect) {
                window.location = redirect;
            } else {
                if (window.location.pathname.toLowerCase() == '/register')
                    window.location = '/';
                else
                    window.location.reload(true);
            }
        }
    });
}

alertMSGLogin = function (msg, referrer) {
    var index = layer.open({
        content: msg
        , btn: ['登入', '立即註冊']
        , yes: function () {
            if (referrer) {
                $('.btnLogin').attr('data-redirect', referrer);
            }
            layer.close(index);
            $('#loginBtn').trigger('click');
        }
        , no: function () {
            layer.close(index);
            //open register form
            window.location = '/register';
        }
    });
}


alertMSGCallback = function(msg, callback) {

    var index = layer.open({
        content: msg,
        shadeClose: false,
        btn: 'OK',
        yes: function() {
            layer.close(index);
            if (callback)
                callback();
        }
    });
};


alertMSGWithdraw = function (callback) {

    var content = "<div>";
    content += "<p style='text-align: left;'>如當日出款次數超過3次，會收取出款手續費用，如同意的話系統會自動批准該筆取款<p>";
    content += "<p style='margin-top: 1.5em;'><label for='withdrawNotice_doNotDisplayAgain' class='checkbox-inline'><input type='checkbox' id='withdrawNotice_doNotDisplayAgain' class='withdrawNotice_doNotDisplayAgain'>不再顯示</label></input></p>";
    content += "</div>";

    var index = layer.open({
        title: '提款提示',
        shadeClose: false,
        content: content,
        btn: ['同意', '取消'],
        yes: function () {

            /* COOKIES */
            var doNotDisplayAgain = $('.withdrawNotice_doNotDisplayAgain').is(":checked");
            if (doNotDisplayAgain) {
                var currentDate = new Date();
                var expirationDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 1, 0, 0, 0);
                $.cookie("withdrawNotice_doNotDisplayAgain", currentDate, { expires: expirationDate, path: "/" });
            }

            layer.close(index);

            callback();
        },
        no: function () {
            layer.close(index);
        }
    });
}

SetPopupOpenerClickEvent = function (url) {
    var event = "window.open('" + url + "', '_blank')";
    $('#btnPopupOpener')
        .attr('onclick', event);
}

alertMSGTransfer = function (callback) {

    var content = "<div>";
    content += "<p style='text-align: left;'><strong>若您的優惠尚未領取，請聯繫客服，若您要直接遊戲，視為放棄此次優惠</strong><p>";
    content += "<p style='margin-top: 1.5em;'><label for='transferNotice_doNotDisplayAgain'><input type='checkbox' id='transferNotice_doNotDisplayAgain' class='transferNotice_doNotDisplayAgain'>不再顯示</label></input></p>";
    content += "</div>";

    var index = layer.open({
        title: '溫馨提醒',
        shadeClose: false,
        content: content,
        btn: ['同意', '取消'],
        yes: function () {

            /* COOKIES */
            var doNotDisplayAgain = $('.transferNotice_doNotDisplayAgain').is(":checked");
            if (doNotDisplayAgain) {
                var currentDate = new Date();
                var expirationDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate() + 1, 0, 0, 0);
                $.cookie("transferNotice_doNotDisplayAgain", currentDate, { expires: expirationDate, path: "/" });
            }

            layer.close(index);

            callback();
        },
        no: function () {
            layer.close(index);
        }
    });
}

Get_AgentTopTen = function () {
    $.ajax(
    {
        url: "/handlers/GetAgentTopTen.ashx",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (result) {
            var o = result;
            var code = o.code;
            var json = JSON.parse(o.data);

            for (i = 0; i <= json.length; i++) {

                if (json[i].nickname != null) {
                    if (json[i].NoOfStar == "1")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip1.png" class="rvip"></span>');
                    else if (json[i].NoOfStar == "2")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip2.png" class="rvip"></span>');
                    else if (json[i].NoOfStar == "3")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip3.png" class="rvip"></span>');
                    else if (json[i].NoOfStar == "4")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip4.png" class="rvip"></span>');
                    else if (json[i].NoOfStar == "5")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip5.png" class="rvip"></span>');
                    else
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip6.png" class="rvip"></span>');
                }

                else {
                    $('.rname' + i + '').html('<span class="spanspecial">尚未設定昵稱</span>');
                }

                $('.ramt' + i + '').html(parseFloat(json[i].Turnover).toFixed(2));
            }
        },
        error: function (e) {
        }
    });
}