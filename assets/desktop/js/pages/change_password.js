
$(function () {
    //fe_transfer
    $('.btnChangePassword').click(function () {
        var param = {
            old_password: $('.container_password .old_password').val(),
            new_password: $('.container_password .new_password').val(),
            confirm_password: $('.container_password .confirm_password').val()
        };

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
