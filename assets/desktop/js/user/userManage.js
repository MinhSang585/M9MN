/**
 * Created by 1040170 on 2018/7/11.
 */

var accountTrueName = $('#j-name').val();
var accountPhone = $('#j-phone').val();

$('.js-menu-list li[href="#tab-withdraw"]').on('click', function () {
    checkPayPasswordStatus()
});

$(window).load(function () {

    if ($('#xinMing').val() != '') {
        $('#xinMing').val($('#xinMing').val());
        $('#xinMing').attr('readonly', 'readonly');
    } else {
        $('#xinMing').removeAttr("readonly");
    }

    $('.js-unbind-close-btn').on('click', function () {
        $('#unbindCardModal').hide();
    });

    // $('li[href="#tab-withdraw"]').on('click', function () {
    //     getBindBankinfo();
    // });

    // 绑定银行卡
    $("#j-bindBankCard").on("click", function () {
        $('#mymodal').fadeIn();
    });

    $('.js-close-btn').on('click', function () {
        $('#mymodal').fadeOut();
    });

    $("#j-bindPayPassword").on("click", bindPayPassword);

    $(".js-resetPayPassword-btn").on("click", resetPayPassword);

    $(".js-setPayPassword").on("click", setPayPassword);


});
$('li[href="#tab-userLetter"]').on('click', function () {
    letterService(1);
});

$('li[href="#tab-deposit"]').on('click', function () {

    // $('#deposit-wexin').trigger('click');

    // if(accountTrueName && accountPhone){
    //
    //
    //
    // }else{
    //     layer.open({
    //         skin: 'top-class tips-layer',
    //         closeBtn: false,
    //         content: '请完善您的姓名及手机号，再申请优惠！',
    //         btn: ['确定','取消'],
    //         yes:function () {
    //             window.location.href="/userManage.php?action=5"
    //         }
    //     });
    //
    //     $("body").addClass("layer-open");
    // }
});


//判断是否设置提款密码
function checkPayPasswordStatus() {

    var phone = $('#j-phone').val();

    if(phone != ''){
        getBindBankinfo();

        $.post('/asp/queryQuestionForApp.php', function (data) {
            if (data) {
                var mykey = '';
                for (var key in data) {
                    mykey = key;
                }
                if (mykey == 0) {

                    $('#j-bindPayPassword').html('修改提款密码').attr('withdrawalstatus', '0');

                } else if (mykey == 3) {

                    $('#j-bindPayPassword').html('设置提款密码').attr('withdrawalstatus', '3');


                    // $('#tk-modal').show();

                    layer.open({
                        btn: ['绑定提款密码'],
                        title: "温馨提示",
                        skin: 'top-class tips-layer',
                        content: "您还没绑定提款密码！",
                        closeBtn: 0,
                        yes: function () {
                            layer.closeAll();
                            bindPayPassword();
                        }
                    });

                }
            }
        });

    }else{
        layer.open({
            btn: ['绑定手机号码及姓名'],
            title: "温馨提示",
            skin: 'top-class tips-layer',
            content: "为了您的账户安全，请完善您的姓名和手机号再进行提款！",
            closeBtn: 0,
            yes: function () {
                location.href ='/userManage.php?action=5&type=1';
                localStorage.setItem('tk','1');
            }
        });
    }




}


//绑定页面显示及显示登入密码或者原提款密码
function bindPayPassword() {

    var withdrawalStatus = $(this).attr('withdrawalstatus');

    $('.js-menu-list:nth-child(4) .list-title').trigger('click');

    $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-withdrawalPassword-set"]').trigger('click');

    if (withdrawalStatus == '0') {

        $('.ui-thirdmenu li[href="#change-withdrawal-password"]').show().trigger('click');
        $('.ui-thirdmenu li[href="#set-withdrawal-password"]').hide();
    } else {
        $('.ui-thirdmenu li[href="#set-withdrawal-password"]').show().trigger('click');
        $('.ui-thirdmenu li[href="#change-withdrawal-password"]').hide();
    }

}

//提交设置提款密码
function setPayPassword() {

    $.post('/asp/queryQuestionForApp.php', function (data) {
        var rData = "", reg = "",
            loginPassword = $('.js-loginpassword').val(),
            newPassword = $('.js-newpassword').val().trim(),

            oldPassword = $('.js-oldpassword').val(),
            newPassword2 = $('.js-newpassword2').val().trim();

        if (data) {
            var mykey = '';
            for (var key in data) {
                mykey = key;
            }
            if (mykey == 0) {

                rData = {
                    content: oldPassword,
                    new_content: newPassword2
                }

            } else if (mykey == 3) {

                rData = {
                    password: loginPassword,
                    new_content: newPassword
                };

            }

            $.post('/asp/change_pwsPayAjax.php', rData, function (data) {
                alert(data);
                if (data == '修改成功') {
                    $('.js-menu-list li[href="#tab-withdraw"]').trigger('click');
                    $('.ui-thirdmenu li[href="#tab-withdraw-box"]').trigger('click');
                }
            })
        }
    });


}

//重置提款密码
function resetPayPassword() {
    var accountName = $('.js-account-name').val();
    var phoneNum = $('.js-phone-num').val();
    var emailNum = $('.js-email-num').val();
    var passwordNum = $('.js-login-password').val();

    openProgressBar();

    $.ajax({
        url: "/asp/unbindQuestion.php",
        type: "post",
        dataType: "text",
        data: {
            accountName: accountName,
            phone: phoneNum,
            email: emailNum,
            password: passwordNum
        },

        success: function (msg) {
            closeProgressBar();
            alert(msg);
        },
    });

}


//存款步骤
function showDepositTeach(type) {

    var deposit = type;

    var depositTeachHtml = "";
    var depositTeachTpl = "    " +
        "<div id='{{tplId}}' class='tab-panel'><div class='text-center relative'>\n" +
        "   <p class='mb25'>{{title}}</p>\n" +
        "   <img src='{{img}}' class='mb25' style='max-width:450px;'/>\n" +
        "   <p class='mb25'>{{desc}}</p>\n" +
        "   <input type='button' class='ui-style ui-btn ui-submit' value='已了解，继续下一步' data-toggle='tab' href='{{back}}'>\n" +
        "   {{next_btn}}{{arrow_right}}{{arrow_left}}</div> " +
        "</div>";

    var leftandrightTpl = "    " +
        "<div id='{{tplId}}' class='tab-panel'><div class='text-center relative'>\n" +
        "   <p class='mb25'>{{title}}</p>\n" +
        "   <div class='clear content'>\n" +
        "   <div class='fl'>\n" +
        "   {{desc}}\n" +
        "   <img src='{{img}}' class='mb25'/>\n" +
        "   </div>\n" +
        "   <div class='fl'>\n" +
        "   {{desc2}}\n" +
        "   <img src='{{img2}}' class='mb25'/>\n" +
        "   </div>\n" +
        "   </div>\n" +
        "   <input type='button' class='ui-style ui-btn ui-submit' value='已了解，继续下一步' data-toggle='tab' href='{{back}}'>\n" +
        "   {{next_btn}}{{arrow_right}}{{arrow_left}}</div> " +
        "</div>";


    var back = $("#tab-deposit .ui-thirdmenu li.active").attr('href');
    var nextBtnTpl = "<input type='button' class='ui-style ui-btn' value='下一步' data-toggle='tab' href='{{next}}'>";
    var arrowLeftTpl = "<img src='/images/deposit/arrow-right.png' class='arrow-left' data-toggle='tab' href='{{prevTpl}}'/>";
    var arrowRightTpl = "<img src='/images/deposit/arrow-left.png' class='arrow-right' data-toggle='tab' href='{{nextTpl}}'/>";

    var jsonData = ajaxPost("/data/deposit/depositTeach.json?v=99");
    if (jsonData == "") {
        return false;
    }

    var dataList = jsonData.data[type];

    for (var i = 0; i < dataList.length; i++) {

        var o = dataList[i];

        var html = "";

        var id = deposit + "-" + i;
        var title = o.title;
        var desc = o.desc;
        var img = o.img;
        var next = "#" + deposit + "-" + (i + 1);
        var prev = "#" + deposit + "-" + (i - 1);

        var type = o.type;

        if (type == "leftandright") {

            var desc2 = o.desc2;
            var img2 = o.img2;

            html = leftandrightTpl

                .replace(/\{\{desc\}\}/g, desc)
                .replace(/\{\{img\}\}/g, img)
                .replace(/\{\{desc2\}\}/g, desc2)
                .replace(/\{\{img2\}\}/g, img2)

        } else {
            html = depositTeachTpl
        }

        html = html
            .replace(/\{\{tplId\}\}/g, id)
            .replace(/\{\{title\}\}/g, title)
            .replace(/\{\{desc\}\}/g, desc)
            .replace(/\{\{img\}\}/g, img)
            .replace(/\{\{next\}\}/g, next)
            .replace(/\{\{back\}\}/g, back);

        if (i == 0) {
            html = html.replace(/\{\{arrow_left\}\}/g, "");
        } else {
            var arrowLeftHtml = arrowLeftTpl.replace(/\{\{prevTpl\}\}/g, prev);
            html = html.replace(/\{\{arrow_left\}\}/g, arrowLeftHtml);
        }

        if (i == dataList.length - 1) {
            html = html
                .replace(/\{\{arrow_right\}\}/g, "")
                .replace(/\{\{next_btn\}\}/g, "");
        } else {
            var arrowRightHtml = arrowRightTpl.replace(/\{\{nextTpl\}\}/g, next);
            var nextBtnHtml = nextBtnTpl.replace(/\{\{next\}\}/g, next);
            html = html
                .replace(/\{\{arrow_right\}\}/g, arrowRightHtml)
                .replace(/\{\{next_btn\}\}/g, nextBtnHtml);
        }

        depositTeachHtml += html;

    }

    $("#tab-teach").html(depositTeachHtml)
    $("#tab-teach .tab-panel").first().addClass("active");
    $("#tab-teach").addClass("active").siblings().removeClass("active");
}


//显示绑定的银行卡
function getBindBankinfo() {

    $.post("/asp/getBindedBankinfos.php", function (returnedData, status) {
        if ("success" == status) {

            var bindBankListStr = '<option value="">请选择提款银行类型</option>';

            if (JSON.stringify(returnedData) != '{}') {
                var jsonData = returnedData.data;
                var num = 0;
                for (var i = 0; i < jsonData.length; i++) {

                    if (jsonData[i].bankname != "久安钱包") {
                        bindBankListStr += '<option value=' + jsonData[i].bankname + '>' + jsonData[i].bankname + '</option>';
                        num++;
                    }
                }
            }

            if (num >= 3) {
                $('#j-bindBankCard').hide();
            } else {
                $('#j-bindBankCard').show();
            }

            $('#tkBank').html(bindBankListStr);

        }
    });

}

//重置
function clearTransferMonery() {
    $("#transferGameMoney").val("");
}


//获取提款银行的状态
function getWithDrawBankStatus(bankname) {
    if (bankname == '') {
        return;
    }
    openProgressBar();

    var status = "ERROR";
    $.ajax({
        type: "POST",
        async: false,
        url: "/asp/getWithDrawBankStatus.php",
        data: {"bankname": bankname},
        error: function (response) {
            closeProgressBar();
            alert(response);
        },
        success: function (response) {
            closeProgressBar();
            status = response;
        }
    });
    return status;
}


//获取提款银行的账号信息
function getWithDrawBankNo(bankname) {
    if (bankname == '') {
        return;
    }
    var status = getWithDrawBankStatus(bankname);
    if (status == "MAINTENANCE") {
        alert("银行系统维护中,请选择其他银行或稍后再试");
        return;
    }
    getbankno(bankname);
}


//获取银行账号
function getbankno(bankname) {
    if (bankname == '') {
        return;
    }
    openProgressBar();
    $.post("/asp/searchBankno.php", {
        "bankname": bankname
    }, function (returnedData, status) {
        if ("success" == status) {
            closeProgressBar();
            if (returnedData == 1) {
                $("#tkAccountNo").val("");
                $("#tkBankAddress").val("none");
            } else {
                var recvData = returnedData.split("|||");
                $("#tkAccountNo").val(recvData[0]);
                $("#tkBankAddress").val(recvData[1]);
            }
        }
    });
}

//提款确认
function tkConfirm() {
    var tkAgree = $("#tkAgree");

    if (!tkAgree.is(":checked")) {
        alert("未选中提款须知！");
        return false;
    }
    var tkPassword = $("#tkPassword").val();
    // if (tkPassword == '') {
    //     alert("[提示]密码不可为空！");
    //     return false;
    // }
    var tkBank = $("#tkBank").val();
    if (tkBank == "") {
        alert("[提示]请选择银行！");
        return false;
    }

    var tkAccountNo = $("#tkAccountNo").val();

    if (tkAccountNo == '') {
        alert("[提示]卡折号不可为空！");
        return false;
    }

    var tkBankAddress = $("#tkBankAddress").val();

    if (tkBankAddress == "") {
        alert("[提示]开户网点不可为空！");
        return false;
    }

    var tkAmount = $("#tkAmount").val();

    if (tkAmount == '') {
        alert("[提示]提款金额不可为空！");
        return false;
    }
    if (tkAmount < 100) {
        alert("[提示]单次提款金额最低100");
        return false;
    }

    if (isNaN(tkAmount)) {
        alert("提款金额只能是数字!");
        return false;
    }

    var rex = /^[0-9]+$/;

    if (!rex.test(tkAmount)) {
        alert("提款金额只能是整数哦。");
        return false;
    }


    var answer = $("#mar_answer").val();

    if (answer == '') {
        alert("提款需要填写提款密码");
        return;
    }
    var accountName = $("#accountName").val();

    var html = "";
    html += '<table class="table-border">';
    html += '<tr>';
    html += '<td>账户姓名:' +accountName + '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>银行名称:' + tkBank + '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>银行账号:' + tkAccountNo + '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td>金额:' + tkAmount + '</td>';
    html += '</tr>';
    html += '<tr>';
    html += '<td><font color="red">温馨提示:如您的注册姓名与您的收款账户姓名不一致,将导致提款失败!请您联系在线客服!</font></td>';
    html += '</tr>';
    html += '</table>';


    layer.open({
        skin: 'withdraw-submit-layer',
        title: ['收款人资料'],
        btn: ['提交', '取消'],
        closeBtn: false,
        type: 1,
        area: ['570px', '370px'],
        shadeClose: false, // 点击遮罩关闭
        content: html,
        yes: function () {
            tkWithdrawal()
        }
    });

}

//提交提款
function tkWithdrawal() {
    var tkAgree = $("#tkAgree");
    var tkPassword = $("#tkPassword").val();
    var tkBank = $("#tkBank").val();
    var tkAccountNo = $("#tkAccountNo").val();
    var tkBankAddress = $("#tkBankAddress").val();
    var tkAmount = $("#tkAmount").val();
    var answer = $("#mar_answer").val();

    $.post("/asp/withdrawNew.php", {
        // "password": tkPassword,
        "bank": tkBank,
        "accountNo": tkAccountNo,
        "bankAddress": tkBankAddress,
        "amount": tkAmount,
        "questionid": 7,
        "answer": answer
    }, function (returnedData, status) {
        layer.closeAll();
        if ("success" == status) {
            if (returnedData == "SUCCESS") {
                _showLayer("提款成功！", '');

                window.location.href = "/userManageNew2.php";
                window.reload();
            } else if (returnedData == "未设置提款密码") {

                layer.open({
                    skin: 'top-class tips-layer',
                    closeBtn: false,
                    content: returnedData,
                    btn: ['去设置'],
                    yes: function () {
                        $('.js-menu-list:nth-child(4) .list-title').trigger('click');
                        $('.js-menu-list:nth-child(4) .list-item').find('li[href="#tab-withdrawalPassword-set"]').trigger('click');
                        _hideLayer();
                    }
                });

                $("body").addClass("layer-open");


            } else {
                _showLayer(returnedData, '');
            }
        }
    });
}


function _showLayer(msg, btn) {

    if (btn == "") {
        btn = '关闭';
    }

    layer.open({
        skin: 'top-class tips-layer',
        closeBtn: false,
        content: msg,
        btn: btn
    });

    $("body").addClass("layer-open");
}

function _hideLayer() {
    closeProgressBar();
    layer.closeAll();
    $("body").removeClass("layer-open")
}

//重置提款
function clearTkWithdrawal() {
    $("#tkPassword").val("");
    $("#tkAccountNo").val("");
    $("#tkAmount").val("");
}


//卡号和银行绑定

if ($('#bindBank').hasClass('active')) {
    bindBankWay();
}

function bindBankWay() {

    var identifycode = $('#bdbankno').val();

    if (identifycode.length < 10) {
        alert('请您输入正确的银行卡号');
    } else {
        $.post("/asp/getBankInfo.php", {
                "bankno": identifycode
            },
            function (returnedData) {
                if (returnedData == "我们不支持此银行卡") {

                    alert(returnedData);
                } else if (returnedData == "您输入的银行卡信息错误") {
                    alert('您所输入的银行卡位数不正确!');
                }
                $('#bdbank').val(returnedData.issuebankname);

            });
    }


}

$('#checkbandingform').on('click', confirmTip);

function confirmTip() {

    layer.alert("请确认信息无误，提交之后无法修改！", {
        skin: 'layui-layer-molv',
        closeBtn: 0,
        yes: function () {
            layer.closeAll();
            checkbandingform();
        }
    });
}

//绑定银行卡
function checkbandingform() {

    var bdName = $.trim($("#xinMing").val());

    if (bdName == "") {
        alert("[提示]姓名不可为空！");
        return false;
    }



    var bdbankno = $("#bdbankno").val();

    if (bdbankno == "") {
        alert("[提示]卡/折号不可为空！");
        return false;
    }


    var bdbank = $("#bdbank").val();
    if (bdbank == "") {

        alert("[提示]银行不能为空！");
        return false;
    }

    var bdpassword = $("#bdpassword").val();
    if (bdpassword == "") {
        alert("[提示]登入密码不可以为空");
        return false;
    }
    var bindingCode = $("#bindingCode").val();

    var bdName1 = '';

    if($("#xinMing").data('value')!=''){
        bdName1 = $("#xinMing").data('value');
    }else{
        bdName1 = $("#xinMing").val();
    }

    $.post("/asp/mainbandingBankno.php", {
        "password": bdpassword,
        "bankname": bdbank,
        "bankno": bdbankno,
        "bankaddress": "none",
        "bindingCode": bindingCode,
        "xinMing": bdName1
    }, function (returnedData, status) {
        if ("success" == status) {
            if (returnedData == "SUCCESS") {

                layer.alert("绑定成功！", {
                    skin: 'layui-layer-molv',
                    closeBtn: 0,
                    yes: function () {
                        // window.location.href ="/userManageNew2.php?action=withdraw";
                        layer.closeAll();
                        $('#mymodal').fadeOut();
                        checkPayPasswordStatus();
                        getBindedBankinfos();
                    }
                });

            } else {
                alert(returnedData);
                clearBandingform();
            }
        }
    });
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
                $("#tab-card-binding tbody").html("<tr><td colspan='5'><button class='addBankCard ui-inline-btn ui-submit'> 添加银行卡+</button></td></tr");
            }


        }
    });


    // 解除绑定弹窗
    $(document).on("click", "#tab-card-binding .unbind", function () {

        var cardNo = $(this).data('cardno'),
            userId = $(this).data('id'),
            bankName = $(this).data('bankname');


        $('#unbindCardModal').fadeIn();
        $('#unbind-bankName').val(bankName);
        $('#unbind-cardNo').val(cardNo);


        $(document).on('click', '.js-unbind-card-btn', function () {

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


    });


    // 绑定银行卡
    $(document).on("click", "#tab-card-binding .addBankCard", function () {
        $('#mymodal').fadeIn();
    });
}

//重置绑定
function clearBandingform() {
    $("#bdbankno").val("");
    $("#bdpassword").val("");
    $("#bdbank").val("");

}


/*************************************/

/*获取备注**/
function getRemarkAgain(obj, bankname) {
    //$("#img"+bankname).trigger("click");
    var bank = "";
    if (bankname == "icbc") {
        bank = "工商银行";
    } else if (bankname == "cmb") {
        bank = "招商银行";
    } else if (bankname == "boc") {
        bank = "中国银行";
    } else if (bankname == "cgbchina") {
        bank = "广发银行";
    } else if (bankname == "bankcomm") {
        bank = "交通银行";
    } else if (bankname == "ccb") {
        bank = "建设银行";
    } else if (bankname == "zfb") {
        bank = "支付宝";
    }
    var code = $("input[name='" + bankname + "InputText']").eq(1).val();

    var username = $("." + bankname + "Name").eq(1).val();

    if (confirm("确定重新生成附言？")) {
        $.post("/asp/getRemarkAgain.php", {
            "bankname": bank,
            "depositCode": code,
            "username": username
        }, function (respData) {
            if (respData.length == 5) {
                alert("订单已生成，请用新的附言进行付款");
                //$(".leaveMsg"+bankname).eq(1).html(respData);
                $(obj).siblings('.fyvalue').html(respData)
                    .siblings('.copy').attr('data-clipboard-text',
                    respData);
            } else {
                alert(respData);
            }
            $('.depositCode').attr('src',
                '/asp/depositValidateCode.php?r=' + Math.random());
        });
    }
}

var p = 0;

function addHF() {
    if (p < 1) {
        $.ajax({
            url: "/asp/thirdPartyLoad2.php",
            type: "post",
            dataType: "text",
            data: "",
            success: function (msg) {
                /* var errorInfo1=$('#errorInfo').val();
                 if(null!=errorInfo1&&errorInfo1.length>0&&msg.length>0){
                 //$('#errorInfoA').html('');
                 } */
                $("#addhf").after(msg);
            }
        });
    }
    p++;
}

function copyText(obj) {
    var rng = document.body.createTextRange();
    rng.moveToElementText(obj);
    rng.scrollIntoView();
    rng.select();
    rng.execCommand("Copy");
    rng.collapse(false);
}


//是否有相同姓名等
function haveSameInfo() {
    var flag = false;
    $.ajax({
        type: "post",
        url: "/asp/haveSameInfo.php",
        async: false,
        success: function (data) {
            if (!(data == 'success')) {
                alert(data);
            } else {
                flag = true;
            }
        }
    });

    return flag;
}


function getbanknoOther(_bankname) {
    var flag = false;
    $.ajax({
        type: "post",
        url: "/asp/searchBankno.php",
        data: "bankname=" + _bankname + "&r=" + Math.random(),
        async: false,
        success: function (data) {
            if (data == 1) {
                $("#yhBankNo").val("");
            } else {
                $("#yhBankNo").val(data.split("|||")[0]);
            }
        }
    });
    return flag;
}


function changexhjf() {
    var signRemit = $("#pointRemit").val();
    $("#xhjf").html(signRemit * 2000);
}


var ifCanValidateDeposit;

function validateDepositBankCardInfo() {
    $.ajax({
        type: "post",
        url: "/asp/getValidateDepositBankInfo.php",
        cache: false,
        async: false,
        success: function (data) {
            if (data != null) {
                $("#validateAmountDepositBankInfoTable")
                    .append(
                        "<tr><td><span class='firstFont'>账户名：</span></td><td><span style='color:red;'>"
                        + data.username
                        + "</td><td><span class='firstFont'>开户行：</span></td><td><span style='color:red;'>"
                        + data.bankname
                        + "</span></td></tr>");
                $("#validateAmountDepositBankInfoTable")
                    .append(
                        "<tr><td><span class='firstFont'>帐号：</span></td><td><span style='color:red;'>"
                        + data.accountno
                        + "</td><td><span class='firstFont'>客服QQ：</span></td><td><span style='color:red;'>800164707</span></td></tr>");
                ifCanValidateDeposit = true;
            } else {
                ifCanValidateDeposit = false;
            }
        },
        error: function () {
            ifCanValidateDeposit = false;
        }
    });
}

function clearNoNum(obj) {
    obj.value = obj.value.replace(/\D|^0/g, "");
}

function hideDiv() {
    $(".setTabCon .money .three").show();
    $(".show-edu").hide();

}

function createValidatedPayOrder() {
    var depositAmount = $("#depositAmountInput").val();
    if (depositAmount == '' || $.trim(depositAmount) == '') {
        alert('转账金额必须在100元以上！');
        return;
    }
    if (depositAmount < 100) {
        alert('转账金额必须在100元以上！');
        return;
    }

    openProgressBar();
    $("#validateAmountDepositBankInfoTable").empty();
    validateDepositBankCardInfo();
    if (ifCanValidateDeposit) {
        $.post(
            "/asp/createValidatedPayOrder.php",
            {
                amount: depositAmount
            },
            function (data) {
                if (data.code == '1') {
                    $(".show-edu").show();
                    $(".setTabCon .money .three").hide();
                    $('#validatedAmountInfo').text(
                        '您需要存款的金额为：' + data.amount
                        + ' 元。 请确保存入该指定金额，否则会导致存款无法到帐。');
                } else {
                    alert(data.msg);
                }
            }).fail(function () {
            alert("生成订单失败");
        }).always(function () {
            closeProgressBar();
        });
    } else {
        closeProgressBar();
        alert("当前系统无法处理额度验证存款，对此给您带来的不便我们深表歉意。");
    }
}

function touzhu() {
    window.open('/queryRecod/queryBettRecord.php');
}




