
var loading_layer;

$(function() {
    //$('.mainNav .tab_deposit').click(function() {
    //    CheckPlayerAllowPG();
    //    CheckPlayerAllowMart();
    //});
    var hostname = window.location.hostname;
    if (is_accessBlog == "1") {
        $(".blogitem").show();
    }
});

showLoading = function () {
    loading_layer = layer.load(0, { time: 10 * 5000, shade: [0.5, '#f5f5f5'] }); //0代表加载的风格，支持0-2
}

closeLoading = function () {
    layer.close(loading_layer);
}

alertMSG = function(msg) {
    var index = layer.alert(msg, {
        skin: 'layer-ext-moon',
        title: '信息',
        btn: 'OK',
        closeBtn: 0,
        success: function() {
            $(document).on('keydown', function() {
                if (event.keyCode == 13) {
                    layer.close(index);
                }
            });
        }
    });
};



alertMSGRes = function (msg, redirect) {
    var index = layer.alert(msg, {
        skin: 'layer-ext-moon',
        title: '信息',
        btn: 'OK',
        closeBtn: 0,
        yes: function() {
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
    alertMSG(msg);
}


alertMSGCallback = function (msg, callback) {
    var index = layer.alert(msg, {
        skin: 'layer-ext-moon',
        title: '信息',
        btn: 'OK',
        closeBtn: 0,
        yes: function () {
            layer.close(index);
            if (callback)
                callback();
        }
    });
}

alertMSGWithdraw = function (callback) {

    var content = "<div style='padding:1em;overflow-wrap: break-word;'>";
    content += "<p><strong>如當日出款次數超過3次，會收取出款手續費用，如同意的話系統會自動批准該筆取款</strong><p>";
    content += "<p style='margin-top: 2em;'><label for='withdrawNotice_doNotDisplayAgain'><input type='checkbox' id='withdrawNotice_doNotDisplayAgain' class='withdrawNotice_doNotDisplayAgain'>不再顯示</label></input></p>";
    content += "</div>";

    var index = layer.open({
        type: 1,
        skin: 'layer-ext-moon',
        closeBtn: 1,
        title: '提款提示',
        anim: 2,
        area: ['360px', '210px'],
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

    var content = "<div style='padding:1em;overflow-wrap: break-word;'>";
    content += "<p><strong>若您的優惠尚未領取，請聯繫客服，若您要直接遊戲，視為放棄此次優惠</strong><p>";
    content += "<p style='margin-top: 2em;'><label for='transferNotice_doNotDisplayAgain'><input type='checkbox' id='transferNotice_doNotDisplayAgain' class='transferNotice_doNotDisplayAgain'>不再顯示</label></input></p>";
    content += "</div>";

    var index = layer.open({
        type: 1,
        skin: 'layer-ext-moon',
        closeBtn: 1,
        title: '溫馨提醒',
        anim: 2,
        area: ['360px', '210px'],
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

getUrlVars = function () {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
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

                //$('.trname' + i + '').html(json[i].PlayerName);
                $('.tramt' + i + '').html(parseFloat(json[i].Turnover).toFixed(2));

                if (json[i].nickname != null) {
                    $('.trname' + i + '').html(json[i].nickname);

                    if (json[i].NoOfStar == "1")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip1.png" class="rvip"></span>');
                    else if(json[i].NoOfStar == "2")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip2.png" class="rvip"></span>');
                    else if(json[i].NoOfStar == "3")
                        $('.rname' + i + '').html('<span> ' + json[i].nickname + ' <img src="/web/assets/images/userpanel/icon-vip3.png" class="rvip"></span>');
                    else if(json[i].NoOfStar == "4")
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

            //alert(json[9].Turnover);
            //
        },
        error: function (e) {
        }
    });
}
