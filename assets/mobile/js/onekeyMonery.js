// 一键回归成功
function onekeyMonery() {

    $('#onetransfer-submit').attr('disabled','disabled');

    var jsonData = ajaxPost("/asp/oneKeyGameMoney.php");
    if (jsonData == null || jsonData == "" || typeof jsonData == "undefined"  || jsonData == '""') {
        alert("一键回归成功!");
        location.reload();
    } else {
        alert(jsonData);

    }
    $('#onetransfer-submit').removeAttr('disabled');
}

function ajaxPost(url, parm){
    var RESULT;
    $.ajax({
        url      : url,
        type     : "post",
        data     : parm,
        cache    : false,
        async    : false,
        timeout  : 3000,
        success: function(jsonData) {
            RESULT = jsonData;
            return RESULT;
        }
    });

    return RESULT;
}

/**
 * Created by USER on 2017/6/22.
 */
