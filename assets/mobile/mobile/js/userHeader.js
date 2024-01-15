/**
 * Created by 1040170 on 2018/5/31.
 */
//专属二维码
$(window).load(function () {
    privateQrcode();
});

function privateQrcode() {

    $.post('/asp/queryQrcode.php', function (data) {
        if (data != "[]") {
            var returnData = JSON.parse(data);
            var timeDetail = returnData[0].timeDetail;
            var codeType = returnData[0].codeChannel === 0 ? 'QQ' : '微信';
            var qycodeHtml = '<div class="code-box"><img  class="code-img" src=' + returnData[0].address + '></div><div  class="tips"><p>' + codeType + '专属客服二维码<br>工作时间:' + timeDetail + '</p></div>';

            $('.js-private-qrcode').show().html(qycodeHtml);
        } else {
            $('.js-private-qrcode').hide();
        }
    })
}

