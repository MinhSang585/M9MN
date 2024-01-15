
var wallet_list = [1, 10, 12, 21, 24, 25, 32, 44, 46, 51, 53, 55, 65, 67, 72];
var timeout = 20; //max wallet get balance seconds
var selected_wallet;

$(document).ready(function () {
    GetMainBalance();
    bind_transferFrom();
    $('.walletIcon[data-val=0]').click();
    //Sys.Application.add_init(BindAccordion);
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
        showLoading();
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

                closeLoading();
                //console.log(item.ID);
            });
        }
        //alert(this.value);
    });
});

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
                $('#mainbalance').html('<img class="refreshimg-main" src="/mobile/Assets/images/refresh.gif" >');
            }, 200);
        },
        success: function (data) {
            setTimeout(function () {
                if (!isNaN(parseFloat(data))) {
                    var amt = numberWithCommas(parseFloat(data).toFixed(2));
                    $('#mainbalance').html(amt);
                }
            }, 200);
           
        },
        complete: function (data) {

        },
        error: function () {
            $('#mainbalance').html("0.00");
        }
    });
}

$(document).on('click', '.walletIcon', function () {
    var siteId = $(this).data('val');

    if (siteId == 0) {

        setTimeout(function () {
            jQuery.each(wallet_list, function (idx, val) {
                var sender = $('.walletIcon[data-val=' + val + ']');
                RefreshWallet(sender);
            });
        }, 500);

    } else {
        RefreshWallet($(this));
    }

    return false;
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
                        $(sender).html('<img class="refreshimg" src="/mobile/Assets/images/refresh.gif" height="20px" width="20px">');
                    }, 200);
                },
                success: function (data) {
                    setTimeout(function () {
                        if (data) {
                            try {
                                if (!isNaN(parseFloat(data))) {
                                    var amt = numberWithCommas(parseFloat(data).toFixed(2));
                                    $(sender).html(amt);
                                } else
                                    $(sender).html(data);
                            } catch (err) {
                                $(sender).html(data);
                            }
                        } else {
                            $(sender).html("0.00");
                        }
                    }, 200);

                },
                complete: function (data) {
                    setTimeout(function () {
                        $(sender).attr("src", "/mobile/assets/img/icon_refresh.png");
                    }, 200);
                },
                error: function () {
                    $(sender).parent().parent().find('p:first').html("0.00");
                }
            });
        }
    } catch (err) {
    }

    return false;
}

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


function refreshall() {
    GetMainBalance();
    $('.walletIcon[data-val=0]').click();
}



RefreshWalletTransfer = function (fromSiteId, toSiteId) {
    var refresh_list = [fromSiteId, toSiteId];
    setTimeout(function () {
        jQuery.each(refresh_list, function (idx, val) {
            var sender = $('.walletIcon[data-val=' + val + ']');
            if (val == 1) {
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
                        $('.walletIcon[data-val=0]').trigger('click');
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
                    if ($('.walletIcon[data-val=10]').html() == "-")
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

