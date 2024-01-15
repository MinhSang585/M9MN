/**
 * Created by Alex.Hu on 2019/7/18.
 */
$(function () {
    unreadCouponCount();
});

function unreadCouponCount() {
    var name = $('#loginname').val();

    if(name !=''){
        $.post('/mobi/mobilefindCouponNum.php',function (returnData) {
            if(returnData.code ==="10000"){

                var data = returnData.data;
                var unRead = data.unReceivedNum;

                if(unRead > 0){
                    $('.js-unread-coupon').show().html(unRead);
                }else{
                    $('.js-unread-coupon').hide();
                }
            }else{
                $('.js-unread-coupon').hide();
            }

        });
    }


}