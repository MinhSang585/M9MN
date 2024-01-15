$(document).ready(function () {
   
});

var timeout = 20; //max wallet get balance seconds
function GetMainBalance() {
    var param = {
        siteId: 1,
    }
    $.ajax({
        url: "/handlers/CheckWalletBalance.ashx",
        type: 'POST',
        data: JSON.stringify(param),
        async: true,
        timeout: 1000 * timeout,
        beforeSend: function () {
            setTimeout(function () {
                $('#mainbalance').html('<img class="refreshimg-main" src="/mobile/Assets/images/refresh.gif" height="20px" width="20px" style="position:relative;top:5px;">');
            }, 200);
        },
        success: function (data) {
            setTimeout(function () {
                $('#mainbalance').html(data);
            }, 200);

        },
        complete: function (data) {

        },
        error: function () {
            $('#mainbalance').html("0.00");
        }
    });
}

transferOutAll = function () {
    showLoading();
    $.ajax(
    {
        url: "/handlers/TransferOutAll.ashx",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (e) {
            var o = JSON.parse(e);
            if (o) {
                var code = o.code;
                if (code == 0) {
                    alertMSGCallback(o.msg, function () {
                        GetMainBalance();
                    });
                } else {
                    alertMSG(o.msg);
                }
            }
        },
        error: function (xhr, textStatus, error) {
            alertMSG('帶回失敗!');
        },
        complete: function () {
            closeLoading();
        }
    });
}

