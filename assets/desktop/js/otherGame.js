/**
 * Created by 1040170 on 2019/2/1.
 */
$(function () {

    $('.js-other-navbar-list').addClass('active').siblings('.active').removeClass('active');
    $('.logo-cont').attr('href','/fish.jsp');
});


$('#transferGameOut').change(function () {

    var type = $(this).val();
    var selfHtml = ' <option value="slot">中心钱包(DT/MG/NT/QT/SW/PNG)</option> ' +
                    '<option value="agin">AG账户(AG真人、AG老虎机、AG捕鱼)</option> ';


    var otherHtml = '<option value="self">iWin账户</option>';

    $('#transferGameIn').empty();

    if (type === "self") {
        $('#transferGameIn').append(selfHtml);
        transferMoneyIn("mwg");
    } else {
        $('#transferGameIn').append(otherHtml);
        transferMoneyIn("self");
    }
});