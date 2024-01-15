
var wallet_list = [1, 10, 12, 21, 24, 25, 32, 44, 46, 51, 53, 55, 65, 67, 72];

var timeout = 20; //max wallet get balance seconds

var PLAYER_WITHDRAW_BANK_LIST;

function GetMainBalance() {

    var param = {
        siteId: 1,
    }
    $.ajax({
        url: "/handlers/CheckWalletBalance.ashx",
        type: 'POST',
        data: JSON.stringify(param),
        async: true,
        timeout: 1000 * timeout,
        beforeSend: function () {
            setTimeout(function () {
                $('#mainbalance').html("-");
            }, 200);
        },
        success: function (data) {
            setTimeout(function () {
                if (!isNaN(parseFloat(data))) {
                    var amt = numberWithCommas(parseFloat(data).toFixed(2));
                    $('#mainbalance').html(amt);
                }
                //$('#mainbalance').html(data);
            }, 200);

        },
        complete: function (data) {

        },
        error: function () {
            $('#mainbalance').html("0.00");
        }
    });
}

$(function () {
    $('.container_withdraw #ddl_w_bank').change(function() {
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
                $('.container_withdraw .w_nickname').parents('li').remove();
                SubmitWithdrawForm();
                GetPlayerNickname();
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


RefreshWallet = function (sender) {
    try {
        var siteId = $(sender).data('val');
        if (siteId > 0) {

            var param = {
                siteId: siteId,
            }

            $.ajax({
                url: "/handlers/CheckWalletBalance.ashx",
                type: 'POST',
                data: JSON.stringify(param),
                async: true,
                timeout: 1000 * timeout,
                beforeSend: function () {
                    setTimeout(function () {
                        $(sender).html('<img src="/web/Assets/images/profile/refresh.gif" height="20px" width="20px" style="margin-top:9px;">');
                    }, 200);
                },
                success: function (data) {
                    setTimeout(function () {
                        if (data) {
                            try {
                                if (!isNaN(parseFloat(data))) {
                                    var amt = numberWithCommas(parseFloat(data).toFixed(2));
                                    $(sender).html(amt);
                                } else {
                                    if (data == "維護中") {
                                        $(sender).attr("style", "color:red !important;");
                                    }
                                    else {
                                        $(sender).attr("style", "color:black !important;");
                                    }
                                    $(sender).html(data);
                                }
                                    
                            } catch (err) {
                                $(sender).html(data);
                            }
                        } else {
                            $(sender).html("0.00");
                        }                        
                    }, 200);
                },
                complete: function (data) {
                },
                error: function () {
                    $(sender).html("读取错误");
                }
            });
        }
    } catch (err) {
    }

    return false;
}


RefreshWalletTransfer = function (fromSiteId, toSiteId) {
    var refresh_list = [fromSiteId, toSiteId];
    setTimeout(function () {
        jQuery.each(refresh_list, function (idx, val) {
            var sender = $('.walletIcon[data-val=' + val + ']');
            if (val == 1){
                GetMainBalance();
                RefreshWallet(sender);
            }
                
            else
                RefreshWallet(sender);
        });
    }, 500);
}

transferOutAll = function () {
    showLoading();
    $.ajax(
    {
        url: "/handlers/TransferOutAll.ashx",
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        success: function (e) {
            var o = JSON.parse(e);
            if (o) {
                var code = o.code;
                if (code == 0) {
                    alertMSGCallback(o.msg, function () {
                        GetMainBalance();
                    });
                } else {
                    alertMSG(o.msg);
                }
            }
        },
        error: function (xhr, textStatus, error) {
            alertMSG('帶回失敗!');
        },
        complete: function () {
            closeLoading();
        }
    });
}

ShowBindBankForm = function() {
    //showLoading();
    //GetBindBankList();

    $('.container_profile').hide();
    $('.container_bindbank').show();
}

ShowNicknameForm = function () {
    $(".container_nickname .nickname").val(null);
    $('.container_profile').hide();
    $('.container_nickname').show();
}


ShowWithdrawForm = function () {
    GetPlayerBankInfo();
}

SavedReturnProfileForm = function () {
    GetPlayerBankInfo();
    BackProfileForm();
}

BackProfileForm = function () {
    $('.container_profile').show();
    $('.container_bindbank').hide();
    $('.container_password').hide();
    $('.container_nickname').hide();
}

ShowResetPasswordForm = function () {

    $(".container_password .old_password, .container_password .new_password, .container_password .confirm_password").val(null);

    $('.container_profile').hide();
    $('.container_password').show();
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

                                if (bankId > 0) {
                                    $(".container_withdraw #ddl_w_bank").append($("<option></option>").val(id).html(bankName));
                                    $(".container_profile #bankname").val(bankName);
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

BindWithdrawBankInfo = function() {
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

function BindAccordion() {
    try {
        (function () {
            var d = document,
                accordionToggles = d.querySelectorAll('.js-accordionTrigger'),
                setAria,
                setAccordionAria,
                switchAccordion,
                touchSupported = ('ontouchstart' in window),
                pointerSupported = ('pointerdown' in window);

            skipClickDelay = function (e) {
                e.preventDefault();
                e.target.click();
            }

            setAriaAttr = function (el, ariaType, newProperty) {
                el.setAttribute(ariaType, newProperty);
            };
            setAccordionAria = function (el1, el2, expanded) {
                switch (expanded) {
                    case "true":
                        setAriaAttr(el1, 'aria-expanded', 'true');
                        setAriaAttr(el2, 'aria-hidden', 'false');
                        break;
                    case "false":
                        setAriaAttr(el1, 'aria-expanded', 'false');
                        setAriaAttr(el2, 'aria-hidden', 'true');
                        break;
                    default:
                        break;
                }
            };
            //function
            switchAccordion = function (e) {
                console.log("triggered");
                e.preventDefault();
                var thisAnswer = e.target.parentNode.nextElementSibling;
                var thisQuestion = e.target;
                if (thisAnswer.classList.contains('is-collapsed')) {
                    setAccordionAria(thisQuestion, thisAnswer, 'true');
                    if ($('.walletIcon[data-val=25]').html() == "-")
                        $('.walletIcon[data-val=0]').click();

                } else {
                    setAccordionAria(thisQuestion, thisAnswer, 'false');
                }
                thisQuestion.classList.toggle('is-collapsed');
                thisQuestion.classList.toggle('is-expanded');
                thisAnswer.classList.toggle('is-collapsed');
                thisAnswer.classList.toggle('is-expanded');

                thisAnswer.classList.toggle('animateIn');
            };
            for (var i = 0, len = accordionToggles.length; i < len; i++) {
                if (touchSupported) {
                    accordionToggles[i].addEventListener('touchstart', skipClickDelay, false);
                }
                if (pointerSupported) {
                    accordionToggles[i].addEventListener('pointerdown', skipClickDelay, false);
                }
                accordionToggles[i].addEventListener('click', switchAccordion, false);
            }
        })();

    } catch (err) {

    }
}

function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}


function historyOnChange() {
    if ($('#MainContent_tsType').val() == "bet") {
        $("#div_historyDate").css("display", "none");
    }
    else {
        $("#div_historyDate").css("display", "block");
    }
}


function refreshall() {
    GetMainBalance();
    $('.walletIcon[data-val=0]').click();
}


$(function () {
    //fe_transfer
    $('.btnNickname').click(function () {
        var param = {
            nickname: $('.container_nickname .nickname').val(),
        };

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
    });
});


GetPlayerNickname = function () {
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
        complete: function() {
            closeLoading();
        }
    });

}

UpdatePlayerNickname = function (nickName) {
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
}
