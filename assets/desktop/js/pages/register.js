
$(function () {
    var queryVal = getUrlVars();
    var ref = queryVal["ref"];
    var ag = queryVal["Ag"];
    if (ref) {
        $('#referralcode').val(ref);
        $('#div_Referral').show();
    }

    if (ag) {
        $('#txtAgent').val(ag);
        $('#div_Agent').show();
    }

    $('.btnTac').click(function () {

        var form = document.getElementById('form_register');
        var formData = new FormData(form);
        var error_msg = "";

        showLoading();
        $.ajax(
            {
                url: "/handlers/RequestTac.ashx",
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (e) {
                    var o = JSON.parse(e);
                    closeLoading();
                    if (o) {
                        alertMSG(o.msg);
                        var code = o.code;
                        if (code == 0) {
                            $('#hidden_preReqID').val(o.hiddenID);
                            var preReqID = $('#hidden_preReqID').val();
                            //alert(preReqID);
                            $('.r_form_input').prop('readonly', true);

                        } else {

                        }
                    }
                },
                error: function (e) {
                }
            });
    });

    $('.btnRegister').click(function () {

        //var form = $('form[name=form_register')[0];
        var form = document.getElementById('form_register');
        var formData = new FormData(form);
        var error_msg = "";
        showLoading();
        $.ajax(
            {
                url: "/handlers/Register.ashx",
                type: 'POST',
                data: formData,
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
                                var FormValue = GetFormItemValue(form);
                                var rpasswordCheck = FormValue['rpassword'];
                                var username = FormValue['username'];
                                var password = FormValue['password'];
                                if (rpasswordCheck == "on") {
                                    localStorage.usrname = username;
                                    localStorage.pass = password;
                                    localStorage.chkbx = true;
                                }
                                else {
                                    localStorage.usrname = '';
                                    localStorage.pass = '';
                                    localStorage.chkbx = false;
                                }
                                autoLogin(username, password);
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

});

autoLogin = function (username, password) {
    var param = {
        username: username,
        password: password,
    };

    $.ajax(
        {
            url: "/handlers/Login.ashx",
            type: 'POST',
            data: JSON.stringify(param),
            async: true,
            success: function (result) {
                var o = JSON.parse(result);
                if (o.Data == "success") {
                    window.location.href = "/";
                } else {
                    alertMSG(o.Data);
                }
            },
            error: function (xhr, textStatus, error) {
            }
        });
}

GetFormItemValue = function (formId) {
    var result = $(formId).serializeArray().reduce(function (obj, item) {
        obj[item.name] = item.value;
        return obj;
    }, {});

    return result;
}