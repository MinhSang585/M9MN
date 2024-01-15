var updatePhoneCodeOld = $("#phone-code").val();

var updatePhoneNumOld = '';
if ($("#phone-num").val().indexOf('*') != -1) {
    updatePhoneNumOld = $("#phone-num").data('num');
} else {
    updatePhoneNumOld = $("#phone-num").val();
}
var updateEmailOld = '';
if ($("#email-info").val().indexOf('*') != -1) {
    updateEmailOld = $("#email-info").data('num');
} else {
    updateEmailOld = $("#email-info").val();
}
var updateAccountNameOld = '';
if ($("#actualName").val().indexOf('*') != -1) {
    updateAccountNameOld = $("#actualName").data('num');
} else {
    updateAccountNameOld = $("#actualName").val();
}


var updateAliasNameOld = $("#updateAliasName").val();
var updateQqOld = $("#updateQq").val();
var updateWeiXingOld = $("#weiXing").val();
var updateBirthdayOld = $("#userBirthday").val();
var phoneCodeOld = $("#phone-code").val();

//个人资料
$('.js-person-info-set').on('click', updateUser);
$('.js-change-password').on('click', updateDatePassword);
$('li[href="#tab-withdrawalPassword-set"]').on('click', checkWidthdrawPassword);

$(window).load(function () {

    var action = getQueryString("action");

    if (action == 'personal') {
        $('.js-menu-list:nth-child(4) .list-title').trigger('click');
        $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-personal"]').trigger('click');
    }
});

$(window).load(function () {
    showUserLevel();
    setBirthday();
});

function checkPhoneEmail() {
    var phoneNum = $('#j-phone').val();
    var emailInfo = $('#email-info').val();
    var actualName = $('#actualName').val();
    var phoneLoginFlag = $('#j-phoneLoginFlag').val();
    if (phoneNum) {


        $.post('/asp/validatePhoneisActivation.php', {phone: phoneNum}, function (data) {
            //console.log(data);
            if (data === "0") {
                $('.js-validate-phone').hide();
            } else {
                $('.js-validate-phone').show();
            }
        });
        $('#phone-num').attr('disabled', true).prev().removeClass('i1');
        $('.js-code-cont').hide();
    } else {
        $('#phone-num').attr('disabled', false).prev().addClass('i1');
        $('.js-code-cont').show();
        $('.js-validate-phone').hide();
    }
    if (actualName) {
        $('#actualName').attr('disabled', true).prev().removeClass('i1');
    } else {
        $('#actualName').attr('disabled', false).prev().addClass('i1');

    }

    if (emailInfo) {
        $('#email-info').attr('disabled', true).prev().removeClass('i1');
    } else {
        $('#email-info').attr('disabled', false).prev().addClass('i1');
    }
}


//手机验证弹窗关闭
$('.js-close-btn').click(function () {

    $('#validate-phone-modal').hide();
});

//手机验证弹窗
$('.js-validate-phone').on('click', function () {


    $('#validate-phone-modal').show();

});

//老用户验证手机号码
$('.js-get-validate').on('click', function () {

    var phone = $('#phone-num1').data('num');
    var loginname = $('#j-loginname').val();
    var smsCode = $('#codeNum').val();

    if (!smsCode) {
        _showLayer('验证码不能为空', '确定');
        return false;
    }
    var data = {
        loginname: loginname,
        phone: phone,
        smsCode: smsCode
    };

    $.post('/asp/alidatePhoneOrEmail.php', data, function (data) {
        if (data.indexOf('验证成功') != -1) {
            layer.open({
                skin: 'tips-layer',
                closeBtn: false,
                content: data,
                btn: '确定',
                yes: function () {
                    window.location.href = "/userManage.php?action=5";
                }
            });

        } else {
            _showLayer(data, '确定');
        }

    });
});

//绑定手机获取验证码
$(document).on('click', '.js-get-message-code', function () {

    var validateCode = $('#not-phone-code').val();

    var phoneRegister = '';
    var type = $(this).data('type');
    if (type === 0) {
        phoneRegister = $('#phone-num').val();
    } else {
        phoneRegister = $('#phone-num1').data('num');
    }


    if (phoneRegister=="") {
        _showLayer('请先填写手机号码', '确定');
        return;
    }


    if(validateCode==""){
        _showLayer("请先输入图形里的数字验证码！", '确定');
        return;
    }



        $.post('/asp/sendSmsCodeForUser.php', {phone: phoneRegister,validateCode:validateCode}, function (data) {
            _showLayer(data, '确定');
        });


        var that = $(this);
        var timeo = 60;
        var timeStop = setInterval(function () {
            timeo--;
            if (timeo > 0) {
                that.text(timeo + '秒重发');
                that.attr('disabled', 'disabled');
            } else {
                timeo = 60;
                that.text('获取验证码');
                clearInterval(timeStop);
                that.removeAttr('disabled');
            }
        }, 1000);

});


//验证手机获取验证码
$(document).on('click', '.js-get-message-code1', function () {

    var validateCode = $('#not-phone-code1').val();

    var phoneRegister = '';
    var type = $(this).data('type');
    if (type === 0) {
        phoneRegister = $('#phone-num').val();
    } else {
        phoneRegister = $('#phone-num1').data('num');
    }


    if (phoneRegister=="") {
        _showLayer('请先填写手机号码', '确定');
        return;
    }


    if(validateCode==""){
        _showLayer("请先输入图形里的数字验证码！", '确定');
        return;
    }



    $.post('/asp/sendSmsCodeForUser.php', {phone: phoneRegister,validateCode:validateCode}, function (data) {
        _showLayer(data, '确定');
    });


    var that = $(this);
    var timeo = 60;
    var timeStop = setInterval(function () {
        timeo--;
        if (timeo > 0) {
            that.text(timeo + '秒重发');
            that.attr('disabled', 'disabled');
        } else {
            timeo = 60;
            that.text('获取验证码');
            clearInterval(timeStop);
            that.removeAttr('disabled');
        }
    }, 1000);

});
//判断是否设置提款密码
function checkWidthdrawPassword() {
    $.post('/asp/queryQuestionForApp.php', function (data) {
        if (data) {
            var mykey = '';
            for (var key in data) {
                mykey = key;
            }
            if (mykey == 0) {

                $('.ui-thirdmenu li[href="#set-withdrawal-password"]').hide();
                $('.ui-thirdmenu li[href="#change-withdrawal-password"]').trigger('click');

            } else if (mykey == 3) {

                $('.ui-thirdmenu li[href="#change-withdrawal-password"]').hide();
                $('.ui-thirdmenu li[href="#set-withdrawal-password"]').trigger('click');
            }
        }
    });
}

function showUserLevel() {

    var level = $('#j-myLevel').val(), str = '';

    switch (level) {
        case"0":
            str = '新会员';
            break;
        case"1":
            str = '忠实会员';
            break;
        case"2":
            str = '星级会员';
            break;
        case"3":
            str = '黄金VIP';
            break;
        case"4":
            str = '白金VIP';
            break;
        case"5":
            str = '钻石VIP';
            break;
        case"6":
            str = '至尊VIP';
            break;
    }

    $('.js-user-level').val(str);
}

//判断生日是否设置
function setBirthday() {

    if (updateBirthdayOld) {

        $('#userBirthday').attr('disabled', true);
    } else {

        $('#userBirthday').attr('disabled', false).addClass('date-item');

    }


}


function updateUser() {
    var updatePhoneCode = $("#phone-code").val();

    var updatePhoneNum = '';
    var updateEmail = '';
    var updateAccountName = '';

    var phoneNum = $("#phone-num").val();
    var accountName = $("#actualName").val();
    var email = $("#email-info").val();

    if (phoneNum.indexOf('*') != -1) {
        updatePhoneNum = $("#phone-num").data('num');
    } else {
        updatePhoneNum = $("#phone-num").val();
    }

    if (accountName.indexOf('*') != -1) {
        updateAccountName = $("#actualName").data('num');
    } else {
        updateAccountName = $("#actualName").val();
    }

    if (email.indexOf('*') != -1) {
        updateEmail = $("#email-info").data('num');
    } else {
        updateEmail = $("#email-info").val();
    }

    var updateAliasName = $("#updateAliasName").val();
    var updateQq = $("#updateQq").val();
    var updateWeiXing = $("#weiXing").val();
    var updateBirthday = $("#userBirthday").val();

    var updatephoneCode = $("#phone-code").val();

    if (
        updateBirthday  == updateBirthdayOld &&
        updateEmail == updateEmailOld &&
        updatePhoneNum == updatePhoneNumOld &&
        updateAccountName == updateAccountNameOld &&
        updateAliasName == updateAliasNameOld &&
        updateQqOld == updateQq &&
        updateWeiXing == updateWeiXingOld &&
        updatephoneCode == updatePhoneCodeOld) {
        _showLayer('资料没有异动！', '关闭');
        return;
    }

    $.post("/asp/newEditUserInfo.php", {
        "accountName": updateAccountName,
        "birthday": updateBirthday,
        "phone": updatePhoneNum,
        "smsCode": updatePhoneCode,
        "email": updateEmail,
        "qq": updateQq,
        "aliasName": updateAliasName,
        "microchannel": updateWeiXing


    }, function (returnedData) {
        if (returnedData === "修改成功") {
            layer.open({
                skin: 'top-class tips-layer',
                closeBtn: false,
                content: returnedData,
                btn: ['确定'],
                yes: function () {
                    layer.closeAll();
                    var type = localStorage.getItem('tk','1');
                    if(type == "1"){

                        location.href = "/userManage.php?action=2";

                        // setTimeout(function () {
                        //     $('.js-menu-list li[href="#tab-withdraw"]').trigger('click');
                        //     $('.ui-thirdmenu li[href="#tab-withdraw-box"]').trigger('click');
                        // },200);

                    }else{
                        window.location.href = "/userManage.php?action=1"
                    }
                }
            });

        } else {
            _showLayer(returnedData, '关闭')
        }
    });
    return false;
}


//银行卡绑定情况
function getBindedBankinfos() {

    $.post("/asp/getBindedBankinfos.php", "", function (returnedData, status) {
        if ("success" == status) {

            // 测试资料
            // var returnedData = '{"data":[{"id":1100953,"loginname":"devtest999","bankno":"********11111111","bankname":"招商银行","bankaddress":"none","addtime":"Apr 11, 2017 12:00:00 AM","flag":0,"bindingTime":"2017-04-11 00:00:00"},{"id":1100958,"loginname":"devtest999","bankno":"********13123131","bankname":"农业银行","bankaddress":"none","addtime":"Apr 27, 2017 12:00:00 AM","flag":0,"bindingTime":"2017-04-27 04:00:00"}],"success":true}';
            // returnedData = JSON.parse(returnedData);


            if (JSON.stringify(returnedData) != '{}') {
                var jsonData = returnedData.data;
                var result = "";
                var num = 0;

                console.log(num)


                for (var i = 0; i < jsonData.length; i++) {

                    if (jsonData[i].bankname != "久安钱包") {

                        result += "<tr>";
                        result += "<td>" + (i + 1) + "</td>";
                        result += "<td>" + jsonData[i].bankname + "</td>";
                        result += "<td>" + jsonData[i].bankno + "</td>";
                        result += "<td>" + jsonData[i].bindingTime + "</td>";
                        result += "<td><button class='unbind ui-inline-btn ui-submit' " +
                            "data-bankname='" + jsonData[i].bankname + "' " +
                            "data-cardno='" + jsonData[i].bankno + "'" +
                            "data-id='" + jsonData[i].id + "'>解绑</button>" +
                            "</td>";

                        result += "</tr>";

                        num++;
                    }
                }


                if (num < 3) {

                    result += "<tr><td colspan='5'><button class='addBankCard ui-inline-btn ui-submit'> 添加银行卡+</button></td></tr>";

                }

                $("#j-bankcard").html(num + "张");
                $("#tab-card-binding tbody").html(result);

            } else {
                $("#j-bankcard").html("0张");
                $("#tab-card-binding tbody").html("<tr><td colspan='5'><button class='addBankCard ui-inline-btn ui-submit'> 添加银行卡+</button></td></tr>");
            }


        }
    });

}

// 解除绑定弹窗
$(document).on("click", "#tab-card-binding .unbind", function () {

    var cardNo = $(this).data('cardno'),
        userId = $(this).data('id'),
        bankName = $(this).data('bankname');


    $('#unbindCardModal').fadeIn();
    $('#unbind-bankName').val(bankName);
    $('#unbind-cardNo').val(cardNo);


    $('.js-unbind-card-btn').on('click', function () {

        var password = $('#js-log-password').val();

        $.post("/asp/unBindBankinfo.php", {
            "bankno": userId,
            "password": password
        }, function (response) {

            layer.alert(response, {
                skin: 'layui-layer-molv',
                closeBtn: 0,
                yes: function () {
                    getBindedBankinfos();
                    $('#unbindCardModal').fadeOut();
                    layer.closeAll();
                }
            });
        })
    });

    // layer.open({
    //     title: false,
    //     btn:['解绑银行卡'],
    //     type: 1,
    //     area: ['570px', '390px'],
    //     skin: 'coupon-layer unbind-layer',
    //     content: unBindCardHtml,
    //     yes:function () {
    //         var password = $('#js-log-password').val();
    //
    //
    //     }
    // });
    //
    // $('.layui-layer-content').css('height','auto');

});


// 绑定银行卡
$(document).on("click", "#tab-card-binding .addBankCard", function () {
    $('#mymodal').fadeIn();
});

//修改密码
function updateDatePassword() {

    var updatePassword = $("#updatePassword").val();
    var updateNew_password = $("#updateNew_password").val();
    var updateSpass2 = $("#updateSpass2").val();

    if (updatePassword == '') {
        alert("[提示]用户旧密码不可为空！");
        return false;
    }
    if (updateNew_password == '') {
        alert("[提示]用户新密码不可为空！");
        return false;
    }
    if (updateSpass2 == '') {
        alert("[提示]用户确认新密码不可为空！");
        return false;
    }
    if (updateSpass2 != "" && (updateSpass2 < 6 || updateSpass2 > 12)) {
        alert("[提示]密码长度必须为6-12位英文字母与数字");
        return false;
    }
    if (updateNew_password != updateSpass2) {
        alert("[提示]两次输入的密码不一致，请核对后重新输入！");
        return false;
    }
    openProgressBar();
    $.post("/asp/change_pws.php", {
        "password": updatePassword,
        "new_password": updateNew_password,
        "sPass2": updateSpass2
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            alert(returnedData);
            window.location.reload();
        }
    });
    return false;
}


$('#av-tab1').click(function () {

    $.post('/asp/getColorStationInfo.php',function (result) {

        var  btn = $('.js-av-btn');

        if(result ==="N"){

            btn.removeClass('active').attr('href','/userManage.php?action=1');

        }else{

            btn.addClass('active').attr('href','/asp/hlmLogin.php');

        }
    });
});



