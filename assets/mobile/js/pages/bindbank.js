$(function() {
    $('.btnSaveBank').click(function (e) {

        e.preventDefault();

        //if (!Confirm()) return false;

        //var form = $('form[name=form_savebank')[0];
        //var formData = new FormData(form);
        var param = {
            ddl_bank: $('.container_bindbank #ddl_bank').val(),
            ply_bankname: $('.container_bindbank #ply_bankname').val(),
            ply_bankacc: $('.container_bindbank #ply_bankacc').val(),
        };
        var error_msg = "";
		/*
        showLoading();
        $.ajax(
        {
            url: "/handlers/SaveBank.ashx",
            type: 'POST',
            data: JSON.stringify(param),
            cache: false,
            contentType: false,
            processData: false,
            success: function(e) {
                var o = JSON.parse(e);
                closeLoading();
                if (o) {
                    var code = o.code;
                    if (code == 0) {
                        alertMSGCallback(o.msg, ShowWithdrawForm);
                    } else {
                        alertMSG(o.msg);
                    }
                }
            },
            error: function(xhr, status, error) {
                //var err = eval("(" + xhr.responseText + ")");
                //alert(err.Message);
            },
            complete: function() {
                closeLoading();
            }
        });
		*/
    });
});

GetBindBankList = function () {
	/*
    $.ajax(
    {
        url: "/handlers/GetBindBankList.ashx",
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
                        //clear dropdown
                        $(".container_bindbank #ddl_bank").find('option').not(':first').remove();
                        var json = JSON.parse(o.data);
                        $.each(json, function (key, item) {
                            $(".container_bindbank #ddl_bank").append($("<option></option>").val(item.ID).html(item.BankName));
                        });
                        $(".container_bindbank #ply_bankname, .container_bindbank #ply_bankacc").val(null);
                    }
                    break;
                case 592:
                    location.href = '/';
                    break;
            }
        },
        error: function (e) {
        },
        complete: function() {
            closeLoading();
        }
    });
	*/
}

BindDepositBank = function () {
	/*
    $.ajax(
    {
        url: "/handlers/BindDepositBank.ashx",
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
                        $.each(json, function (key, item) {
                            var bankaccount = item.BankName + " [ " + item.AccountNumber + " - " + item.AccountName + " ] ";
                            //alert(bankaccount);
                            $("#ddl_depo_bank").append($("<option></option>").val(item.ID).html(bankaccount));
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

