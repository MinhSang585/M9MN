﻿
var PLAYER_WITHDRAW_BANK_LIST;

$(function () {
    //GetDepoWithSetting();

    $('.container_withdraw #ddl_w_bank').change(function () {
        BindWithdrawBankInfo();
    });


    $('.btnWithdraw').click(function () {

        if (!$('.container_withdraw #ddl_w_bank').val() || parseFloat($('.container_withdraw #ddl_w_bank').val()) <= 0) {
            alertMSG('請選擇銀行!');
            return false;
        }
        if (!$('.container_withdraw .w_amt').val() || parseFloat($('.container_withdraw .w_amt').val()) <= 0) {
            alertMSG('請輸入金額!');
            return false;
        }
        if (!$('.container_withdraw .w_password').val()) {
            alertMSG('請輸入密碼!');
            return false;
        }
        if ($('.container_withdraw .w_nickname').length > 0) {
            if ($('.container_withdraw .w_nickname').val() == '') {
                alertMSG('請輸入暱稱!');
                return false;
            }
        }
        if ($('.container_withdraw .w_nickname').length > 0) {
            this.updateplayerRes = JSON.parse(UpdatePlayerNickname($('.container_withdraw .w_nickname').val()));
            if (this.updateplayerRes.code == 0 || this.updateplayerRes.code == "0") {
                $('.container_withdraw .w_nickname').parents('.input_relative').remove();
                SubmitWithdrawForm();
            }
            else
                alertMSG('更新暱稱失敗請再嘗試！');
        }
        else
            SubmitWithdrawForm();
        /* COOKIES */
        //var withdrawNotice_doNotDisplayAgain = $.cookie("withdrawNotice_doNotDisplayAgain");

        //if (withdrawNotice_doNotDisplayAgain) {
        //    SubmitWithdrawForm();
        //} else {
        //    alertMSGWithdraw(SubmitWithdrawForm);
        //}
    });
});

ShowBindBankForm = function () {
    showLoading();
    GetBindBankList();

    $('.container_withdraw').hide();
    $('.container_bindbank').show();
}

ShowWithdrawForm = function () {
    
    GetPlayerBankInfo();

    $('.container_withdraw').show();
    $('.container_bindbank').hide();
}


GetPlayerBankInfo = function () {
    showLoading();
    $.ajax(
    {
        url: "/handlers/GetPlayerBankInfo.ashx",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function (result) {
            if (result) {
                var o = result;
                var code = o.code;
                switch (code) {
                    case 0:
                        if (o.data) {
                            var data = JSON.parse(o.data);

                            //assign for global use
                            PLAYER_WITHDRAW_BANK_LIST = data;

                            //clear dropdown
                            $(".container_withdraw #ddl_w_bank").find('option').not(':first').remove();

                            $.each(data, function (i, d) {
                                var id = d.ID;
                                var bankId = d.BankID;
                                var bankName = d.BankName;
                                var accountName = d.AccountName;
                                var accountNumber = d.AccountNumber;

                                if (parseInt(bankId) > 0) {
                                    $(".container_withdraw #ddl_w_bank").append($("<option></option>").val(id).html(bankName));
                                    //$(".container_withdraw #ddl_w_bank").val(bankId).trigger('change');
                                }
                            });

                            BindWithdrawBankInfo();
                        }
                        break;
                    case 592:
                        location.href = '/';
                        break;
                    default:
                        //alertMSG(o.msg);
                        break;
                }
            }
        },
        error: function (xhr, textStatus, error) {
        },
        complete: function() {
            closeLoading();
        }
    });

}

BindWithdrawBankInfo = function () {
    var data = PLAYER_WITHDRAW_BANK_LIST;

    var bankNo = $('.container_withdraw #ddl_w_bank').val();
    if (bankNo > 0) {
        var bankObj = data.filter(
            function (data) { return data.ID == bankNo }
        );
        if (bankObj) {
            var accountName = bankObj[0].AccountName;
            var accountNumber = bankObj[0].AccountNumber;
            $('.container_withdraw #w_ply_bankname').val(accountName);
            $('.container_withdraw #w_ply_bankacc').val(accountNumber);
        }
    } else {
        $('.container_withdraw #w_ply_bankname').val(null);
        $('.container_withdraw #w_ply_bankacc').val(null);
    }
}

SubmitWithdrawForm = function () {
    var param = {
        bankNo: $('.container_withdraw #ddl_w_bank').val(),
        accountName: $('.container_withdraw #w_ply_bankname').val(),
        accountNumber: $('.container_withdraw #w_ply_bankacc').val(),
        w_amt: $('.container_withdraw .w_amt').val(),
        w_password: $('.container_withdraw .w_password').val(),
    };
    showLoading();
    $.ajax(
    {
        url: "/handlers/Withdraw.ashx",
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
                    //location.reload(true);
                    alertMSGRes(o.msg);
                } else {

                    alertMSG(o.msg);
                }
            }
        },
        error: function (e) {
        }
    });
};

GetDepoWithSetting = function () {
	/*
    $.ajax(
    {
        url: "/handlers/GetDepoWithSetting.ashx",
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
                        //alert(json.Withdraw);
                        //$("#min_withdraw").html(json.Withdraw);
                        $.each(json, function (key, item) {
                            $("#min_deposit").html(item.Deposit);
                            $("#hid_min_deposit").val(item.Deposit);

                            $("#min_withdraw").html(item.Withdraw);
                            $("#hid_min_withdraw").val(item.Withdraw);
                        });


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
	*/
}

UpdatePlayerNickname = function (nickName) {
    /*
	var param = {
        nickname: nickName,
    };

    var jqXHR = $.ajax({
        url: "/handlers/ChangeNickname.ashx",
        type: 'POST',
        data: JSON.stringify(param),
        async: false,
        contentType: "application/json",
        dataType: 'JSON',
        success: function (result) {
        },
        error: function (xhr) {
        }
    });

    return jqXHR.responseText;
	*/
}