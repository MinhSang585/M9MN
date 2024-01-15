/**
 * Created by 1040170 on 2019/2/1.
 */


$(function () {
    $('.js-sport-navbar-list').addClass('active').siblings('.active').removeClass('active');
    $('.logo-cont').attr('href', '/sportGame.jsp');

});


$('#transferGameOut').change(function () {

    var type = $(this).val();

    var lotteryHtml = '<option value="sba">沙巴体育账户</option><option value="xj">iWin体育账户</option>';

    var otherHtml = '<option value="self">iWin账户</option>';

    $('#transferGameIn').empty();

    if (type === "self") {
        $('#transferGameIn').append(lotteryHtml);
        transferMoneyIn("fanya");
    } else {
        $('#transferGameIn').append(otherHtml);
        transferMoneyIn("self");
    }
});