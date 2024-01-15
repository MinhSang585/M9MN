$(function () {
    $('select.promotion').change(function () {
        var selectedIndex = $(this).prop('selectedIndex');
        var seletedval = $('select.promotion').val();
        //alert(seletedval)
        if (selectedIndex == 0) {
            $(this).css('color', 'black');
        }
        else
            $(this).css('color', '#376594');
    });
})

GetBindPromotion = function () {
    $("#ddl_pg_promotion").empty();
    $("#ddl_manual_promotion").empty();
    $("#ddl_mart_promotion").empty();
    $.ajax(
    {
        url: "/handlers/BindPromotion_dropdown.ashx",
        type: 'POST',
        cache: false,
        async: true,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (result) {
            var o = result;
            var code = o.code;
            switch (code) {
                case 0:
                    if (o.data) {
                        var json = JSON.parse(o.data);
                        $("#ddl_pg_promotion").append($("<option></option>").val(0).html('請選擇優惠'));
                        $("#ddl_manual_promotion").append($("<option></option>").val(0).html('請選擇優惠'));
                        $("#ddl_mart_promotion").append($("<option></option>").val(0).html('請選擇優惠'));
                        //console.log("promotion" + json);
                        $.each(json, function (key, item) {

                            if (item.PromoId == "353") {
                                $("#ddl_pg_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "5000以上（聯繫客服領取）"));
                                $("#ddl_manual_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "5000以上（聯繫客服領取）"));
                                $("#ddl_mart_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "5000以上（聯繫客服領取）"));
                            }
                            else {
                                $("#ddl_pg_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "（請聯繫客服領取）"));
                                $("#ddl_manual_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "（請聯繫客服領取）"));
                                $("#ddl_mart_promotion").append($("<option></option>").val(item.PromoId).html(item.PromoDesc + "（請聯繫客服領取）"));
                            }

                        });
                    }
                    break;
                case 592:
                    location.href = '/';
                    break;
                case 999:
                    $("#ddl_pg_promotion").append($("<option></option>").val("-1").html('已領取全部優惠'));
                    $("#ddl_manual_promotion").append($("<option></option>").val("-1").html('已領取全部優惠'));
                    $("#ddl_mart_promotion").append($("<option></option>").val("-1").html('已領取全部優惠'));

                    $("#ddl_pg_promotion").addClass("extracol");
                    $("#ddl_manual_promotion").addClass("extracol");
                    $("#ddl_mart_promotion").addClass("extracol");
                    //$(".special_promotion").hide();
                    break;
            }
        },
        error: function (e) {
        }
    });
}