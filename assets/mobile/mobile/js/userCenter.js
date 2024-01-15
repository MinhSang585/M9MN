/**
 * Created by 1040170 on 2018/5/15.
 */

header.find('.header-title').text('账户中心');
footer.find('.item.account').addClass('active').find('i').addClass('active');
var accountTrueName = $('#j-name').val();
var accountPhone = $('#j-phone').val();
// $(window).load(function () {
//     getGuestbookCountNew();
// });
function deposit() {
    // if (accountPhone && accountTrueName) {

    // } else {
    //     linkSet();
    // }

}

function qd() {
    // if (accountPhone && accountTrueName) {
        mobileManage.redirect('signBonus');
    // } else {
    //     linkSet();
    // }
}

function linkSet() {

    // layer.open({
    //     skin: 'top-class tips-layer',
    //     closeBtn: false,
    //     content: '请完善您的姓名及手机号，再申请优惠！',
    //     btn: ['确定', '取消'],
    //     yes: function () {
    //         window.location.href = "/mobile/account/info.jsp"
    //     }
    // });
    //
    // $("body").addClass("layer-open");
}

function getGuestbookCountNew() {

    if (getCookie(COOKIE_ITEM["getGuestbookCountNew"])) {
        var response = getCookie(COOKIE_ITEM["getGuestbookCountNew"]);
        $("#emailcount").text("（" + response + "条未读）");
    } else {

        $.post("/asp/getGuestbookCountNew.php", function (response) {
            if (response > 0) {
                $("#emailcount").text("（" + response + "条未读）");
                setCookie(COOKIE_ITEM["getGuestbookCountNew"], response);
            }
        });
    }
}

function dosign() {
    $.ajax({
        url: "/asp/doSignRecord.php",
        type: "post",
        dataType: "text",
        success: function (msg) {
            alert(msg);
        },
        error: function () {
            window.location.href = "${ctx}/";
        }
    });
}
