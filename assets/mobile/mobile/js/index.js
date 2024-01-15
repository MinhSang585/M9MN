/**
 * Created by 1040170 on 2018/5/7.
 */

$(window).load(function () {
    

    //$('.main-wrap .icon.flaticon-speaker3').click(function () {
    //    mobileManage.redirect('news');
    //});
});





//进入第一个画面
function firstPageCount() {

    var $first = $('.first-page');
    $first.find('.hand').css({bottom: 30});
    $first.find('.download').click(function () {
        getAppVersionCustomInfo();
    });
}



//  首頁轮播





