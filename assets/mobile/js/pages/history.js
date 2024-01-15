$(function () {
    $('.btnHistory').click(function () {
        SubmitHistoryForm();
    });
});

function historyOnChange() {
    if ($('#tsType').val() == "bet") {
        $("#div_historyDate").css("display", "none");
    }
    else {
        $("#div_historyDate").css("display", "block");
    }
}



SubmitHistoryForm = function () {

    var param = {
        history_val: $('.container_history #tsType option:selected').val(),
        start_date: $('.container_history .history_start_date').val(),
    };

    var launch = false;

    showLoading();
    $.ajax(
    {
        url: "/handlers/History.ashx",
        type: 'POST',
        data: JSON.stringify(param),
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (e) {
            var o = JSON.parse(e);
            closeLoading();
            if (o) {
                var code = o.code;
                if (code == 0) {
                    var url = o.msg;
                    if (url) {
                        SetPopupOpenerClickEvent(url);
                        launch = true;
                    }
                } else {
                    alertMSG(o.msg);
                }
            }
        },
        error: function (xhr) {
            var msg = eval("(" + xhr.responseText + ")");
            alertMSG(msg);
            return false;
        }
    });

    if (launch == true)
        $('#btnPopupOpener').trigger('click');

    return false;
};


