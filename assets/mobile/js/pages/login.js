$(function () {

    $('.button_login').click(function () {
        var param = {
            username: $('.container_login .username').val(),
            password: $('.container_login .password').val(),
        };
        $.ajax(
            {
                url: "/handlers/Login.ashx",
                type: 'POST',
                data: JSON.stringify(param),
                async: true,
                success: function (result) {
                    var o = JSON.parse(result);
                    closeLoading();
                    if (o.Data == "success") {
                        //alertMSGCallback("Line@官方客服 6/23 23:00～6/24 6:00 進行維護 ，這段時間要找客服請使用微信喔～", function () {
                        //    window.location.href = "/";
                        //});

                        window.location.href = "/";
                    }
                    //else if (o.Data == "nicknameNeeded") {
                    //    alertMSGCallback("現在有暱稱功能囉，快去用戶中心填寫吧", function () {
                    //        window.location.href = "/";
                    //    });
                    //}
                    else {
                        alertMSG(o.Data);
                    }
                    //if (o.Data == "success") {
                    //    var path = getUrlVars()["path"];
                    //    if (!path)
                    //        window.location.href = "/";
                    //    else {
                    //        if (path == "roulette")
                    //            window.location.href = "/event/roulette";
                    //        else if (path == "slot")
                    //            window.location.href = "/event/slot";
                    //    }
                    //} else {
                    //    alertMSG(o.Data);
                    //}
                },
                error: function (xhr, textStatus, error) {
                }
            });
    });

    $('.btnLogout').click(function () {
        $.ajax(
            {
                url: "/handlers/Logout.ashx",
                type: 'POST',
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    if (result) {
                        if (result.code == 0) {
                            window.location.href = '/m';
                        }
                    } else {
                        alertMSG(result.msg);
                    }
                },
                error: function (xhr, textStatus, error) {
                }
            });
    });

});

function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
