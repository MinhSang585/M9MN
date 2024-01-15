
$(function () {
    //fe_transfer
    $('.btnChangePassword').click(function () {
        var param = {
            old_password: $('.container_password .old_password').val(),
            new_password: $('.container_password .new_password').val(),
            confirm_password: $('.container_password .confirm_password').val()
        };
		
		/*
        showLoading();
        $.ajax(
        {
            url: "/handlers/ChangePassword.ashx",
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
                        alertMSG(o.msg);
                    }
                    else {
                        alertMSG(o.msg);
                    }
                }
            },
            error: function (e) {
            }
        });
		*/
    });
});

ShowBindBankForm = function () {
    showLoading();
    GetBindBankList();

    $('.container_profile').hide();
    $('.container_bindbank').show();
}

ShowWithdrawForm = function () {
    GetPlayerBankInfo();
}

SavedReturnProfileForm = function () {
    GetPlayerBankInfo();
    BackProfileForm();
}

ShowNicknameForm = function () {
    $(".container_nickname .nickname").val(null);
    $('.container_profile').hide();
    $('.container_nickname').show();
}


BackProfileForm = function () {
    $('.container_profile').show();
    $('.container_bindbank').hide();
    $('.container_nickname').hide();
    $('.container_password').hide();
}

ShowResetPasswordForm = function () {

    $(".container_password .old_password, .container_password .new_password, .container_password .confirm_password").val(null);

    $('.container_profile').hide();
    $('.container_password').show();
}

GetPlayerBankInfo = function () {
	/*
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
                            //$(".container_withdraw #ddl_w_bank").find('option').not(':first').remove();

                            $.each(data, function (i, d) {
                                var id = d.ID;
                                var bankId = d.BankID;
                                var bankName = d.BankName;
                                var accountName = d.AccountName;
                                var accountNumber = d.AccountNumber;

                                if (bankId > 0) {
                                    //$(".container_withdraw #ddl_w_bank").append($("<option></option>").val(id).html(bankName));
                                    $(".container_profile #bankname").val(bankName);
                                    //$(".container_withdraw #ddl_w_bank").val(bankId).trigger('change');
                                }
                            });
                            //BindWithdrawBankInfo();
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
        complete: function () {
            closeLoading();
        }
    });
	*/
}


$(function () {
    //fe_transfer
    $('.btnNickname').click(function () {
        var param = {
            nickname: $('.container_nickname .nickname').val(),
        };
		/*
        showLoading();
        $.ajax(
        {
            url: "/handlers/ChangeNickname.ashx",
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
                        alertMSG(o.msg);
                        GetPlayerNickname();
                        BackProfileForm();
                    }
                    else {
                        alertMSG(o.msg);
                    }
                }
            },
            error: function (e) {
            }
        });
		*/
    });
});


GetPlayerNickname = function () {
	/*
    showLoading();
    $.ajax(
    {
        url: "/handlers/GetPlayerNickname.ashx",
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
                            $.each(data, function (i, d) {
                                $(".container_profile #nickname").val(d.nickname);
                            });
                        }
                        break;
                    default:
                        //alertMSG(o.msg);
                        break;
                }
            }
        },
        error: function (xhr, textStatus, error) {
        },
        complete: function () {
            closeLoading();
        }
    });
	*/
}
