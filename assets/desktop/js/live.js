/**
 * Created by 1040170 on 2019/2/1.
 */

$(function () {
    $('.js-live-navbar-list').addClass('active').siblings('.active').removeClass('active');
    $('.logo-cont').attr('href','/live.jsp');

});

$('#transferGameOut').change(function () {

    var type = $(this).val();
    var selfHtml = '<option value="ea">EA真人账户</option> ' +
        '<option value="ebetapp">EBET真人账户</option>' +
        '<option value="agin">AG国际账户</option>' +
        ' <option value="agqj">AG旗舰账户</option>' +
        ' <option value="sunbet">申博真人账户</option> ' +
        ' <option value="live">MG真人账户</option>' +
        '<option value="bbin">BBIN真人账户</option>';


    var otherHtml = '<option value="self">iWin账户</option>';

    $('#transferGameIn').empty();

    if (type === "self") {
        $('#transferGameIn').append(selfHtml);
        transferMoneyIn("ea");
    } else {
        $('#transferGameIn').append(otherHtml);
        transferMoneyIn("self");
    }
});


