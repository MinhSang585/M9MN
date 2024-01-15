
$(document).ready(function () {

    bind_transferFrom();

});


$(function () {
    //fe_transfer
    $('.btnTransfer').click(function () {
        var wal_from = $('#ddlTFrom').find(":selected").val();
        var wal_to = $('#ddlTTo').find(":selected").val();
        var amt = $('#txtTranferAmount').val();

        if (wal_from == "0") {
            alert('請選擇從轉帳');
            return;
        }

        if (wal_to == "0") {
            alert('請選擇轉入帳戶');
            return;
        }

        if (amt == "") {
            alert("請輸入金額");
            return;
        }

        var param = {
            trf_from: wal_from,
            trf_to: wal_to,
            trf_amt: amt
        };

        showLoading();
        $.ajax(
        {
            url: "/handlers/TransferCredit.ashx",
            type: 'POST',
            data: JSON.stringify(param),
            cache: false,
            contentType: false,
            processData: false,
            success: function (e) {
                var o = JSON.parse(e);
                closeLoading();
                if (o) {
                    var code = o.code;
                    if (code == 0) {
                        alertMSGCallback(o.msg, function () {
                            RefreshWalletTransfer(wal_from, wal_to);
                        });
                    } else {
                        alertMSG(o.msg);
                    }
                }

            },
            error: function (e) {
            }
        });
    });

    $("#ddlTFrom").change(function () {
        //showLoading();
        $('#ddlTTo').empty();
        if (this.value == "1" || this.value == "0") {
            $("#ddlTTo").append($("<option></option>").val(0).html("— 請選擇 —"));
            $.each(selected_wallet, function (key, item) {
                if (item.ID != "1")
                    $("#ddlTTo").append($("<option></option>").val(item.ID).html(item.WalletDesc_CHT));

                closeLoading();
                //console.log(item.ID);
            });
        }
        else {
            $.each(selected_wallet, function (key, item) {
                if (item.ID == "1")
                    $("#ddlTTo").append($("<option></option>").val(item.ID).html(item.WalletDesc_CHT));

                //closeLoading();
                //console.log(item.ID);
            });
        }
        //alert(this.value);
    });
});

bind_transferFrom = function () {
    $.ajax(
    {
        url: "/handlers/GetSiteWalletList.ashx",
        type: 'POST',
        cache: false,
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
                        selected_wallet = json;
                        $("#ddlTFrom").empty();
                        $("#ddlTFrom").append($("<option></option>").val(0).html("— 請選擇 —"));
                        $("#ddlTTo").append($("<option></option>").val(0).html("— 請選擇 —"));
                        $.each(json, function (key, item) {
                            $("#ddlTFrom").append($("<option></option>").val(item.ID).html(item.WalletDesc_CHT));
                            $("#ddlTTo").append($("<option></option>").val(item.ID).html(item.WalletDesc_CHT));
                        });
                        //$("#ddlTTo").append($("<option></option>").val(0).html("— 請選擇 —"));
                        //$('#ddlTFrom').change();
                    }
                    break;
                case 592:
                    location.href = '/';
                    break;
            }
        },
        error: function (e) {
        }
    });
}

