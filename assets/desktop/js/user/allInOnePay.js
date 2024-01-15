var _TEST_MODE = 0;
var _SUBMIT_FLAG = false;

var _PAYWAY_URL = "";
var _HOSTNAME = window.location.hostname;

if (_HOSTNAME == "172.18.62.17") {
    _TEST_MODE = 1;
} else {
    _TEST_MODE = 0;
}

var _NEW_MC = 1;
if (_NEW_MC == 1) {
    $(".j-new-mc").hide();
    $(".j-old-mc").hide();
} else {
    $(".j-new-mc").hide();
    $(".j-old-mc").show();
}

var _MC_WECHAT = 1;
if (_MC_WECHAT != 1) {
    $("#deposit-wechattransfer").hide();
    $("#deposit-wechattransfer-kid").hide();
}

$.ajaxSetup({
    beforeSend: function (jqXHR, settings) {
        _loadLayer();
    },
    complete: function () {
        layer.closeAll('loading');
    }
});

$(function () {

    console.log("iWin国际 - 诚信值千亿 千万诚信 赢得天下");
    console.log("|ˉˉˉˉˉˉˉˉˉˉˉ|  \\ˉˉ\\    /ˉˉ/");
    console.log("| |ˉˉˉˉˉˉˉ| |   \\  \\  /  / ");
    console.log("| |  \\ˉˉ\\ | |    \\  \\/  /");
    console.log("| |   \\  \\| |     \\    /  ");
    console.log("| |    \\  \\ |      |  |    ");
    console.log("|  ˉˉˉˉˉ\\  \\|      |  |  ");
    console.log(" ˉˉˉˉˉˉˉˉ\\  \\      |  |");
    console.log("          ˉˉˉ      ˉˉˉˉ ");

    // 初始化
    DepositPage();

});


function DepositPage() {

    // 点击事件
    clickEvent();

    var _dep = [];
    var _deposit = [];
    var _payUrl = '/asp/pay_api.php';
    var _formHtml = '<form action="{0}" method="post" target="_blank">{1}</form>';
    var _inputHtml = '<input type="hidden" name="{0}" value="{1}"/>';

    _init();

    /**
     * 初始化
     */
    function _init() {

        if (_TEST_MODE == 0) {
            _PAYWAY_URL = '/asp/pay_way.php';
        } else {
            _PAYWAY_URL = '/data/deposit/payWay99.json?v=2';
        }

        var payWayData = "";

        $.post(_PAYWAY_URL, {"usetype": "2"}, function (data, status) {
            if (status == "success") {

                try {
                    payWayData = JSON.parse(data);
                } catch (e) {
                    payWayData = data;
                }

                if (payWayData && payWayData.desc == '成功') {
                    for (var i in payWayData.data) {

                        var o = payWayData.data[i];

                        // 11/11 快捷支付最高上限2000，超过2000容易风控
                        if (o.payWay == 4) {
                            if (o.maxPay > 2000) {
                                o.maxPay = "2000";
                            }
                        }

                        if ($.isArray(_deposit[o.payWay])) {
                            _deposit[o.payWay].push(o);
                        } else {
                            _deposit[o.payWay] = new Array();
                            _deposit[o.payWay].push(o);
                        }
                    }
                    _hideLayer();
                    _filterPayPage(payWayData); // 页面开关
                } else {
                    _hideLayer();
                    _showLayer(payWayData.desc);
                }
            }
        });

        // 点标题，初始化支付方式
        $(".ui-thirdmenu li").bind('click', function (e) {
            _titleClickEvent(this, "menu");
        });

        $("#tab-bankcard li").bind('click', function (e) {
            _titleClickEvent(this, "bank");
        });

        _getDepositMark();
        _fastPayManage();
    }

    function _filterPayPage(payWayData) {

        if (payWayData.desc == '成功') {

            // 判断通道维护
            for (var key in _deposit) {

                var $tab = "", $fpanel = "", $tag = "", $spanel = "";

                key = parseInt(key);

                switch (key) {
                    case 1:
                        $tab = "#deposit-zfb";
                        $fpanel = "#tab-zfb";
                        $tag = "#deposit-zfbQR";
                        $spanel = "#tab-zfbQR";
                        break;
                    case 2:
                        $tab = "#deposit-wexin";
                        $fpanel = "#tab-weixin";
                        $tag = "#deposit-wexinQR";
                        $spanel = "#tab-wechat";
                        break;
                    case 3:
                        $tab = "#deposit-bankcard";
                        $fpanel = "#tab-bankcard";
                        $tag = "#deposit-thirdpay";
                        $spanel = "#tab-thirdpay";
                        break;
                    case 4:
                        $tab = "#deposit-bankcard";
                        $fpanel = "#tab-bankcard";
                        $tag = "#deposit-speedpay";
                        $spanel = "#tab-speedpay";
                        break;
                    case 7:
                        $tab = "#deposit-qqpay";
                        $fpanel = "#tab-qqpay";
                        $tag = "#deposit-qq";
                        $spanel = "#tab-qq";
                        break;
                    case 10:
                        $tab = "#deposit-jdpay";
                        $fpanel = "#tab-jdpay";
                        $tag = "#deposit-jd";
                        $spanel = "#tab-jd";
                        break;
                    case 13:
                        $tab = "#deposit-lian";
                        $fpanel = "#tab-lian";
                        $tag = "#deposit-yinlian";
                        $spanel = "#tab-yinlian";
                        break;
                    default:
                        break;
                }

                $($tab).removeClass("hidden");
                $($fpanel).removeClass("hidden");
                $($tag).removeClass("hidden");
                $($spanel).addClass("active");

            }

            // 默认tab
            $(".ui-thirdmenu li:visible").eq(0).trigger("click");
        }
    }

    function _titleClickEvent(e, type) {

        if (!_dep[e.id]) {
            switch (e.id) {
                case 'deposit-wexin':
                    _dep[this.id] = new _WeixinManage();
                    break;
                case 'deposit-zfb':
                    _dep[this.id] = new _ZFBManage();
                    break;
                case 'deposit-qqpay':
                    _dep[this.id] = new _QQManage();
                    break;
                case 'deposit-thirdpay':
                    _dep[this.id] = new _ThirdPayManage();
                    break;
                case 'deposit-speedpay':
                    _dep[this.id] = new _SpeedPayManage();
                    break;
                case 'deposit-jdpay':
                    _dep[this.id] = new _JDManage();
                    break;
                case 'deposit-lian':
                    _dep[this.id] = new _YinlianManage();
                    break;
                default:
            }
        }

        var targetId = "#" + e.id;
        var $href = $(targetId).attr("href");

        if (type != "bank") {
            $($href).find(".payment-item:visible").eq(0).trigger("click");

        }

        $(".money-input").val("").show();
        $(".ui-submit").addClass("disabled").removeAttr("disabled");
    }

    function _buildHtml(type, title, arr) {

        var _result = "";

        // 支付宝
        if (type == 1) {

            var payWayTpl = '<button type="button" class="payWayBtn" data-url="{{payCenterUrl}}" ' +
                'data-toggle="tab" ' +
                'href="#zfb-pay1"' +
                'data-id="{{id}}"  ' +
                'data-payway="{{payway}}"  ' +
                'data-max={{maxPay}} ' +
                'data-min="{{minPay}}" ' +
                'data-platform="{{platform}}" ' +
                'data-fee="{{fee}}"><i class="{{icon}}"></i><span>{{showName}}</span>{{recommend}}</button>';
        }
        // 微信
        else if (type == 2) {

            var payWayTpl = '<button type="button" class="payWayBtn" data-url="{{payCenterUrl}}" ' +
                'data-toggle="tab" ' +
                'href="#wechat-pay1"' +
                'data-id="{{id}}"  ' +
                'data-payway="{{payway}}"  ' +
                'data-max={{maxPay}} ' +
                'data-min="{{minPay}}" ' +
                'data-platform="{{platform}}" ' +
                'data-fee="{{fee}}"><i class="{{icon}}"></i><span>{{showName}}</span>{{recommend}}</button>';
        } else {
            var payWayTpl = '<button type="button" class="payWayBtn" data-url="{{payCenterUrl}}" ' +
                'data-id="{{id}}"  ' +
                'data-payway="{{payway}}"  ' +
                'data-max={{maxPay}} ' +
                'data-min="{{minPay}}" ' +
                'data-platform="{{platform}}" ' +
                'data-fee="{{fee}}"><i class="{{icon}}"></i><span>{{showName}}</span>{{recommend}}</button>';
        }

        if (!arr || arr == "" || arr.length < 1) {
            return false;
        }

        for (var i = 0; i < arr.length; i++) {

            var o = arr[i];
            var payWayHtml = "", _icon = "";

            payWayHtml = payWayTpl.replace(/\{\{payCenterUrl\}\}/g, o.payCenterUrl)
                .replace(/\{\{id\}\}/g, o.id)
                .replace(/\{\{payway\}\}/g, o.payWay)
                .replace(/\{\{maxPay\}\}/g, o.maxPay)
                .replace(/\{\{minPay\}\}/g, o.minPay)
                .replace(/\{\{fee\}\}/g, o.fee)
                .replace(/\{\{platform\}\}/g, o.payPlatform)
                .replace(/\{\{style\}\}/g, "")
                .replace(/\{\{hot\}\}/g, "")
                .replace(/\{\{showName\}\}/g, title + (i + 1));

            if (type == 1) {
                _icon = "iconfont icon-zhifubao";
            } else if (type == 2) {
                _icon = "iconfont icon-weixinzhifu";
            } else if (type == 3) {
                _icon = "iconfont icon-zaixianzhifu";
            } else if (type == 4) {
                _icon = "iconfont icon-kuaijiezhifu";
            } else if (type == 5) {
                _icon = ""; // 点卡
            } else if (type == 6) {
                _icon = ""; // 秒存
            } else if (type == 7) {
                _icon = "iconfont icon-qqzhifu";
            } else if (type == 8) {
                _icon = "iconfont icon-wannengzhifu";
            } else if (type == 10) {
                _icon = "iconfont icon-jingdongzhifu";
            } else {
                _icon = "";
            }

            payWayHtml = payWayHtml.replace(/\{\{icon\}\}/g, _icon);

            if (o.lockFlag == "Y") {
                payWayHtml = payWayHtml.replace(/\{\{recommend\}\}/g, '<i class="recommend-icon"></i>');
            } else {
                payWayHtml = payWayHtml.replace(/\{\{recommend\}\}/g, '');
            }

            _result += payWayHtml;
        }

        return _result;
    }

    function _getDepositMark() {

        // var getDepositMarkUrl = "";
        //
        // if (_TEST_MODE == 1) {
        //     getDepositMarkUrl = "/data/deposit/getDepositMark.json";
        // } else {
        //     getDepositMarkUrl = "/asp/getDepositMark.php";
        // }
        //
        // try {
        //     $.post(getDepositMarkUrl, function (response) {
        //
        //         if (response.code == "10000") {
        //             var mark = response.mark;
        //             var dividend = response.dividend;
        //
        //             if (mark == true) {
        //                 $(".give-gifts").text("赠" + dividend + "%").show();
        //             } else {
        //                 $(".give-gifts").text("").hide();
        //             }
        //         }
        //
        //     });
        // } catch (e) {
        //
        // }

        $(".give-gifts").text("赠1%").show();

    }

    /***
     * 支付宝/网银/手机 秒存
     */
    function _fastPayManage() {

        var saveName = "", saveMoney = "", saveType = "", saveBank = "", saveTypeCN = "", saveDepositId = "",
            renewData = "";

        var $panel = "";

        var $saveName = $(".quick-save-username");
        var $saveMoney = $(".quick-save-money");
        var $saveSubmitBtn = $(".quick-save-next");
        var $saveSuccessBtn = $(".bank-success");
        var $saveRenewBtn = $(".bank-renew");

        var $quota = $(".quota");
        var $saveCheck = $("#must-checked");
        var $saveCheck2 = $("#must-checked1");
        var $quotaSubmitBtn = $("#j-jumpNext");

        var $getHistoryBtn = $(".chkbankhistory");

        var fastpayClick = 0;

        function _init() {

            $saveSubmitBtn.off("click").on("click", function (e) {
                var $that = $(this);
                _chkInfo($that);
            });

            //2018
            $saveSuccessBtn.off("click").on("click", function () {
                _resetMiaoCun($panel);
            });

            $saveRenewBtn.off("click").on("click", function () {

                var newDepositUrl = "";

                if (_TEST_MODE == 1) {
                    newDepositUrl = "/data/deposit/getNewdeposit1.json";
                } else {
                    if (saveType == 88) {
                        newDepositUrl = "/asp/getNewdeposit_v2.php";
                    } else {
                        newDepositUrl = "/asp/getNewdeposit.php";
                    }
                }

                layer.confirm('此操作将作废当前订单，页面会获取新的银行卡，请重新存款。', {
                    skin: 'tips-layer',
                    title: '温馨提示',
                    closeBtn: false,
                    btn: ['换卡', '取消'], //按钮
                    offset: ['36%', '38%']
                }, function () {

                    renewData['force'] = true;
                    renewData['depositId'] = _getDepositId();

                    $.post(newDepositUrl, renewData, function (jsonData) {
                        if (jsonData.massage && jsonData.massage != "") {
                            _hideLayer();
                            _showLayer(jsonData.massage);
                            return false;
                        } else {
                            _hideLayer();
                            _setDepositInfo(jsonData);
                        }
                    });

                }, function () {
                    _hideLayer();
                });
            });

            $getHistoryBtn.off("click").on("click", function () {
                if (!window['historyList']) {
                    window['historyList'] = _getHistoryList();
                }
            });

            $(document).on("click", ".quick-save-choose-btn", function () {
                var name = $(this).data("name");

                $saveName.val(name);
                _hideLayer();
            });

            $(document).on("click", ".quick-save-delete-btn", function () {
                var $that = $(this);
                var $parent = $that.parents("tr");

                var card = $(this).data("card");
                var loginname = $(this).data("loginname");
                var depositid = $(this).data("depositid");

                if (_TEST_MODE == 1) {
                    $parent.remove();
                } else {

                    var formData = {
                        "loginname": loginname,
                        "ubankno": card,
                        "depositId": depositid
                    }

                    $.post("/asp/updateDepositBank.php", formData, function (jsonData) {

                        if (jsonData == "删除成功") {
                            $parent.remove();
                        } else {
                            _showLayer(jsonData);
                        }
                    });

                }
            });


        }

        function _chkInfo($btn) {

            var $activeBtn = "", name_msg = "", money_msg = "";

            $panel = $btn.parents(".tab-cont.active").children(".tab-panel.active");

            saveName = $panel.find(".quick-save-username").val();
            saveMoney = parseInt($panel.find(".quick-save-money").val());

            if (_NEW_MC == 1) {

                $activeBtn = $panel.find(".j-new-mc .payWayBtn.active");
                saveType = $activeBtn.data("banktype");
                saveBank = $activeBtn.data("bankname");

            } else {
                $activeBtn = $panel.find(".j-old-mc .payWayBtn.active");
                saveType = $activeBtn.data("banktype");

            }

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            if (name_msg == "" && (saveName == '' || saveName == null || !saveName)) {
                name_msg = "请填写您的存款姓名";
            }
            var myReg = /^[\u4e00-\u9fa5]+$/;
            if (name_msg == "" && !myReg.test(saveName)) {
                name_msg = "只能填写汉字，不能输入其他字符。";
            }

            if (name_msg != "") {
                $(".quicknametr").find(".ui-message").html(name_msg).removeClass('ui-hidden');
                return false;
            } else {
                $(".quicknametr").find(".ui-message").html("").addClass('ui-hidden');
            }

            if (money_msg == "" && (saveMoney == '' || saveMoney == null || !saveMoney)) {
                money_msg = "请输入金额";
            }
            if (money_msg == "" && isNaN(saveMoney)) {
                money_msg = "金额不得包含汉字!";
            }
            if (money_msg == "" && (saveMoney < $min || saveMoney > $max)) {
                if ($max > 9999) {
                    money_msg = "请输入" + $min + "元到" + (($max / 10000)) + "万元之间的金额";
                } else {
                    money_msg = "请输入" + $min + "元到" + $max + "元之间的金额";
                }
            }

            var reg = /^[1-9]\d*$/;
            if (money_msg == "" && !reg.test(saveMoney)) {
                money_msg = "请输入整数！";
            }

            if (money_msg != "") {
                $(".quickmoneytr").find(".ui-message").html(money_msg).removeClass('ui-hidden');
                return false;
            } else {
                $(".quickmoneytr").find(".ui-message").html("").addClass('ui-hidden');
            }

            _miaoCun();
        }

        function _miaoCun() {

            $saveSubmitBtn.attr("disabled", true);
            fastpayClick = 0;

            var formData = {
                banktype: saveType,
                uaccountname: saveName,
                amount: saveMoney
            };

            if (saveType == 77 || saveType == 60 || saveType == 69) {
                formData['ubankname'] = saveBank;
            }

            if (saveType == 77 || saveType == 69) {

                var quotaUrl = "";

                if (_TEST_MODE == 1) {
                    quotaUrl = "/data/deposit/getWxZzQuota.json";
                } else {
                    if (saveType == 88) {
                        quotaUrl = "/asp/getWxZzQuota_v2.php";
                    } else {
                        quotaUrl = "/asp/getWxZzQuota.php";
                    }
                }

                // 获取小数点
                $.post(quotaUrl, formData, function (response) {

                    if (response.code == "10000") {

                        $quota.html("<font color='red'>实际转账金额：" + response.data + "（不包含手续费）</font>");

                        $("#tab-fastpay").addClass("active").siblings().removeClass("active");
                        $("#tab-fastpay-9").addClass("active").siblings().removeClass("active");

                        // 同意书
                        $quotaSubmitBtn.off("click").on("click", function (e) {
                            e.preventDefault();
                            if ($saveCheck.prop('checked') && $saveCheck2.prop('checked')) {

                                // 创建订单
                                _getDeposit(formData);

                            } else {
                                _showLayer("请同意iWin存款条例！");
                            }
                        });
                    } else {
                        _showLayer(response.message);
                        _resetMiaoCun($panel);
                    }

                });

            } else {

                // 创建订单
                _getDeposit(formData);

            }

        }

        function _getDeposit(formData) {

            fastpayClick++;

            if (fastpayClick != 1) {
                _showLayer("操作异常，请勿连续点击!");
                return false;
            }

            // 重新获取
            renewData = formData;

            var newDepositUrl = "";

            if (_TEST_MODE == 1) {
                newDepositUrl = "/data/deposit/newDepositForce.json";
            } else {
                if (saveType == 88) {
                    newDepositUrl = "/asp/getNewdeposit_v2.php";
                } else {
                    newDepositUrl = "/asp/getNewdeposit.php";
                }
            }

            $.post(newDepositUrl, formData, function (jsonData) {

                if (jsonData.massage && jsonData.massage) {
                    var massage = jsonData.massage;
                }

                if (massage && massage == "使用秒存请先绑定真实姓名") {

                    _showLayer(massage);
                    _resetMiaoCun($panel);

                    return false;
                }

                if (!massage) {

                    _showDepositInfo(jsonData);
                    _setDepositId(jsonData.depositId);

                } else {

                    if (jsonData['force'] == true) {

                        _setDepositId(jsonData.depositId);

                        layer.confirm('<span class="red">您上一笔订单未支付，可点击确定作废订单，系统将生成新的存款信息，按存款信息存款即可立即到账。</span>', {
                            skin: 'tips-layer',
                            title: '温馨提示',
                            closeBtn: false,
                            btn: ['确定', '取消'], //按钮
                            offset: ['36%', '38%']
                        }, function () {

                            _hideLayer();

                            fastpayClick++;

                            if (fastpayClick != 2) {
                                _showLayer("操作异常，请勿连续点击!");
                                return false;
                            }

                            formData['force'] = true;

                            if (_TEST_MODE == 1) {
                                newDepositUrl = "/data/deposit/getNewdeposit2.json";
                            } else {
                                if (saveType == 88) {
                                    newDepositUrl = "/asp/getNewdeposit_v2.php";
                                } else {
                                    newDepositUrl = "/asp/getNewdeposit.php";
                                }
                            }

                            $.post(newDepositUrl, formData, function (jsonData) {

                                if (!jsonData.massage) {
                                    _showDepositInfo(jsonData);
                                    return true;
                                } else {
                                    _showLayer(jsonData.massage);
                                    _resetMiaoCun($panel);
                                    return false;
                                }
                            });

                        }, function () {
                            _hideLayer();
                            _resetMiaoCun($panel);
                            return false;
                        });

                    } else {
                        _hideLayer();
                        _showLayer(jsonData.massage);
                        _resetMiaoCun($panel);
                        return false;
                    }

                }
            });
        }

        function _showDepositInfo(response) {

            if (saveType == 1) {
                $(".j-bank-tip").show();
            } else {
                $(".j-bank-tip").hide();
            }

            // if (saveType == 77 || saveType == 60) {
            //     $saveRenewBtn.show();
            // } else {
            //     $saveRenewBtn.hide();
            // }

            if (saveType == '60') {
                saveTypeCN = "网银转账";
            } else if (saveType == '77') {
                saveTypeCN = "支付宝转账";
            } else if (saveType == '69') {
                saveTypeCN = "微信转账";
            } else if (saveType == '88') {
                saveTypeCN = "支付宝扫码";
            }

            $("#fastpay-area").hide();

            if (saveType == "1" || saveType == "00") {
                if (response.area == "" || typeof response.area == 'undefined') {
                    $("#fastpay-area").hide();
                } else {
                    if (response.amount > 49999) {
                        $("#fastpay-area").show();
                    } else {
                        $("#fastpay-area").hide();
                    }
                }
            }

            if (saveType == 88) {
                $(".fastpay-type-2").show();
                $(".fastpay-type-1").hide();

                if (response.qrCodeUrl && response.qrCodeUrl != "") {
                    _setQrcode(response.qrCodeUrl);
                } else {
                    _showLayer("系统繁忙");
                    _resetMiaoCun($panel);
                    return false;
                }

            } else {
                $(".fastpay-type-1").show();
                $(".fastpay-type-2").hide();
            }

            if ($('.quick-save-money').val() >= 50000) {
                layer.open({
                    title: ["提示"],
                    content: '大额跨行转账可能会延时到账，假日期间建议使用同行进行大额转账！'
                });
            }

            _setDepositInfo(response);
        }

        function _setQrcode(url) {

            try {

                $(".qrCodeUrl").html("");
                $(".qrCodeUrl").qrcode({
                    render: "canvas", // 渲染方式有table方式和canvas方式
                    width: 200,   //默认宽度
                    height: 200, //默认高度
                    text: url, //二维码内容
                    typeNumber: -1,   //计算模式一般默认为-1
                    correctLevel: 2, //二维码纠错级别
                    background: "#ffffff",  //背景颜色
                    foreground: "black"  //二维码颜色
                });

                // canvas 转 jpg
                setTimeout(function () {
                    var canvasDataUrl = $('.qrCodeUrl canvas')[0].toDataURL("image/png");
                    $(".qrCodeImg").html("<img src=" + canvasDataUrl + ">");
                }, 200)

            } catch (e) {
                /* do something */
            }
        }

        function _setDepositInfo(response) {

            _setDepositId(response.depositId);

            $(".quick-confirm-type").val(saveTypeCN);
            $(".quick-confirm-username").val(saveName);

            $(".fastpay-confirm-money").html("￥" + response.amount + "&nbsp;");
            $("span.quick-confirm-money").html(response.amount);
            $("input.quick-confirm-money").val(response.amount);

            var accountno = addBlank(response.accountno);
            $("#sbankname").val(response.bankname);
            $("#saccountno").val(accountno);
            $("#saccountname").val(response.username);
            $("#sacarea").val(response.area);

            $("#saccountno").next().attr("data-clipboard-text", response.accountno);
            $("#saccountname").next().attr("data-clipboard-text", response.username);

            $("#tab-fastpay").addClass("active").siblings().removeClass("active");
            $("#tab-fastpay-2").addClass("active").siblings().removeClass("active");

            $saveCheck.attr("checked", false);
            $saveCheck2.attr("checked", false);

        }

        function _getHistoryList() {

            $.post("/asp/queryDepositBank.php", function (jsonData) {
                if (jsonData && typeof jsonData != 'undefined') {
                    var pageContents = jsonData.pageContents;

                    var html = "<table>";
                    if (pageContents.length > 0) {
                        html += "<tr><td>编号</td><td>姓名</td><td>功能</td></tr>";
                        var pageLength = (pageContents.length > 5) ? 5 : pageContents.length;
                        for (var i = 0; i < pageLength; i++) {
                            var uaccountname = (pageContents[i].uaccountname) ? pageContents[i].uaccountname : "";
                            var loginname = (pageContents[i].loginname) ? pageContents[i].loginname : "";
                            var depositid = (pageContents[i].depositId) ? pageContents[i].depositId : "";

                            if (uaccountname != "") {
                                html += "<tr>";

                                html += "<td>" + (i + 1) + "</td>";
                                html += "<td>" + uaccountname + "</td>";
                                html += "<td>";
                                html += "<input type=\"button\" value=\"选中\" class=\"quick-save-choose-btn ui-style ui-btn\" data-name='" + uaccountname + "'>";
                                html += "<input type=\"button\" value=\"删除\" class=\"quick-save-delete-btn ui-style ui-btn\" data-depositid='" + depositid + "' data-loginname='" + loginname + "'>";
                                html += "</td>";

                                html += "</tr>";
                            }
                        }

                    } else {
                        html = '<tr><td colspan="5">暂无历史记录</td></tr>';
                    }

                    html += "</table>";

                    layer.open({
                        skin: 'historyList',
                        title: ["历史姓名"],
                        area: ['600px'],
                        content: html
                    });
                }
            });
        }

        function _resetMiaoCun($panel) {

            var $parent = $panel.parents(".tab-cont");

            renewData = "";

            $saveName.val('');
            $saveMoney.val('');
            $quota.val('');

            $saveCheck.attr("checked", false);
            $saveCheck2.attr("checked", false);

            $saveSubmitBtn.attr('disabled', false);

            $(".quicknametr").find(".ui-message").html("").addClass('ui-hidden');
            $(".quickmoneytr").find(".ui-message").html("").addClass('ui-hidden');

            $parent.addClass("active").siblings().removeClass("active");
            $panel.addClass("active").siblings().removeClass("active");

            fastpayClick = 0;
        }

        function _setDepositId(id) {
            saveDepositId = id;
        }

        function _getDepositId() {
            return saveDepositId;
        }

        _init();
    }

    /***
     * 支付宝扫码
     */
    function _ZFBManage() {

        var $parent = $("#tab-zfbQR");
        var $payway = 1;

        function _initComponent() {
            $(document).on("click", "#deposit-zfbQR2-submit", _doPay);
            $(document).on("click", "#deposit-zfbQR2-reset", _reset);
        }

        function _queryPayWay() {

            $parent.find(".payway-list").html(_buildHtml(1, "支付宝支付", _deposit[$payway]));

            // $parent.find('.payway-list button:eq(0)')
            //     .css({"position": "relative"})
            //     .append('<i class="recommend-icon"></i>');

            var prependHtml = "";

            // 支付宝扫码
            // prependHtml = '<button type="button" class="payWayBtn" data-min="1" data-max="200"  data-banktype="9" data-toggle="tab" href="#zfb-pay2"><i class="iconfont icon-zhifubao"></i><span>支付宝扫码</span></button>';

            $parent.find(".payway-list").prepend(prependHtml);
        }

        function _doPay() {

            var $activeBtn = $parent.find(".payWayBtn.active");

            var formData = {
                usetype: 2,
                loginName: $("#j-loginname").val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr("data-id"),
                payUrl: $activeBtn.attr("data-url")
            };

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);
            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
            }
            if (msg != "") {
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");
                _payTo(formData);
            }

        }

        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _queryPayWay();
        _initComponent();
    }

    /***
     * 微信扫码
     */
    function _WeixinManage() {

        var $parent = $("#tab-wechat");
        var $payway = 2;

        function _init() {
            _queryPayWay();
            $(document).on("click", "#deposit-wexinQR-submit", _doPay);
            $(document).on("click", "#deposit-wexinQR-reset", _reset);
        }

        function _queryPayWay() {

            $parent.find(".payway-list").html(_buildHtml(2, "微信支付", _deposit[$payway]));

            var prependHtml = "";

            // 微信扫码
            // prependHtml = '<button type="button" class="payWayBtn" data-min="1" data-max="200"  data-banktype="8" data-toggle="tab" href="#wechat-pay2"><i class="iconfont icon-weixinzhifu"></i><span>微信扫码</span></button>';
            // $parent.find(".payway-list").prepend(prependHtml);
        }

        function _doPay() {

            var $activeBtn = $parent.find(".payWayBtn.active");

            var formData = {
                usetype: 2,
                loginName: $("#j-loginname").val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr("data-id"),
                payUrl: $activeBtn.attr("data-url")
            };

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);
            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
            }

            if (msg != "") {
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");

                if (formData.platformId == "wxQuick") {
                    window.open("/asp/wxDeposit.php", true);
                } else {

                    var platform = $activeBtn.data("platform");

                    if (platform == "nttwx") {

                        var arr = ["中信银行", "中国银行", "兴业银行", "上海银行", "浦东发展银行", "平安银行", "民生银行", "华夏银行", "光大银行", "北京银行", "中国农业银行", "中国建设银行"];
                        var str = arrToStr(arr);

                        layer.confirm(str, {
                            title: '温馨提示', //按钮
                            btn: ['确定', '取消'], //按钮
                            offset: ['36%', '38%']
                        }, function () {
                            _payTo(formData);
                            _hideLayer();
                        });

                    } else {
                        _payTo(formData);

                    }

                }
            }
        }


        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();
    }

    /***
     * QQ钱包
     */
    function _QQManage() {

        var $parent = $('#tab-qq');
        var $payway = 7;

        function _init() {
            _queryPayWay();
            $(document).on("click", "#deposit-qq-submit", _doPay);
            $(document).on("click", "#deposit-qq-reset", _reset);
        }

        function _queryPayWay() {
            $parent.find(".payway-list").html(_buildHtml(7, "QQ支付", _deposit[$payway]));
        }

        function _doPay() {

            var $activeBtn = $parent.find('.payWayBtn.active');

            var formData = {
                usetype: 2,
                loginName: $("#j-loginname").val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr("data-id"),
                payUrl: $activeBtn.attr("data-url"),
                bank_code: "ZF_WX"
            };

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);

            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
            }
            if (msg != "") {
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");

                _payTo(formData);

            }
        }

        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();
    }

    /***
     * 在线支付
     */
    function _ThirdPayManage() {

        var $parent = $('#tab-thirdpay');
        var $payway = 3;

        function _init() {
            $("#bank-list, #third-list").html("");
            _queryPayWay();
            _initComponent();
        }

        function _initComponent() {
            $(document).on("click", "#deposit-thirdpay-submit", _doPay);
            $(document).on("click", "#deposit-thirdpay-reset", _reset);

            $(document).on("click", "#tab-thirdpay .payway-list .payWayBtn", function () {

                var payUrl = $parent.find(".payway-list .payWayBtn.active").attr("data-url");

                if (payUrl == "/kht/zfb_wx" || payUrl == "/qsx/zfb_wx" || payUrl == "/nhh/zfb_wx") {

                    $parent.find("#tab-thirdpay-2").addClass("ui-hidden");
                    $parent.find("#tab-thirdpay-3").removeClass("ui-hidden");

                } else {

                    $parent.find("#tab-thirdpay-2").addClass("ui-hidden");
                    $parent.find("#tab-thirdpay-3").addClass("ui-hidden");
                    _queryPayBank();
                }
            });
        }

        function _queryPayWay() {
            $parent.find(".payway-list").html(_buildHtml(3, "在线支付", _deposit[$payway]));
        }

        function _queryPayBank() {

            var payBankUrl = "";

            if (_TEST_MODE == 0) {
                payBankUrl = "/api/pay/thirdPaymentBank/list";
            } else {
                payBankUrl = "/data/deposit/payBank.json";
            }

            var _dataUrl = $parent.find(".payway-list .payWayBtn.active").attr("data-url");
            var $platformIdArr = _dataUrl.split("/");
            var $platformId = $platformIdArr[1] + $platformIdArr[2];

            if ($platformId == "" || typeof $platformId === 'undefined') {
                return false;
            }

            $.ajax({
                url: payBankUrl,
                type: "POST",
                data: JSON.stringify({payType: $platformId}), //dbonline_pay
                dataType: "JSON",
                contentType: "application/json",
                async: 'true',
                success: function (response) {
                    if (response.code == "10000") {
                        _buildBankHtml(response);
                    } else {
                        alert("请稍后重试");
                    }
                }
            });

            $("#tab-thirdpay-2").removeClass("ui-hidden");
        }

        function _buildBankHtml(jsonData) {

            var dataList = jsonData.data;

            var bankHtml = "";
            var bankTpl = '<button type="button" class="bank-btn"' + 'data-value="{{dictValue}}"><img src="{{imageUrl}}"><span>{{dictName}}</span></button>';


            // $.post("/data/deposit/bankIconList.json?v=2", function (response) {

            // var bankIconList = response;

            $.each(dataList, function (i, vo) {
                var o = dataList[i];
                // var icon = bankIconList[o.bankZHName];

                bankHtml += bankTpl.replace(/\{\{dictValue\}\}/g, o.bankCode)
                    .replace(/\{\{dictName\}\}/g, o.bankZHName)
                    .replace(/\{\{imageUrl\}\}/g, o.imageUrl);
            });

            $(".bank-list").html(bankHtml);
            $(".money-input").text("");

            $(".bank-btn").off("click").on("click", function () {
                $(this).addClass("active").siblings().removeClass("active");
                $("#tab-thirdpay-3").removeClass("ui-hidden");
            });

            // });


        }

        function _doPay() {

            var $activeBtn = $parent.find('.payway-list .payWayBtn.active');

            var formData = {
                usetype: 2,
                loginName: $('#j-loginname').val(),
                orderAmount: $parent.find(".money-input").val(),

                platformId: $activeBtn.attr("data-id"),
                payUrl: $activeBtn.attr("data-url"),
                bankCode: $parent.find('.bank-list .bank-btn.active').attr("data-value")
            };

            if ($("#j-bank-choose").is(":visible")) {
                if (formData.bankCode == "" || typeof formData.bankCode == 'undefined') {
                    $parent.find(".bank-error-message").removeClass("ui-hidden");
                } else {
                    $parent.find(".bank-error-message").addClass("ui-hidden");
                }
            } else {
                delete formData["bankCode"];
            }

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);
            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
                $parent.find("#tab-thirdpay-3").find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find("#tab-thirdpay-3").find(".ui-message").addClass("ui-hidden");
                _payTo(formData);
            }
        }

        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();
    }

    /***
     * 快捷支付
     */
    function _SpeedPayManage() {

        var $parent = $("#tab-speedpay");
        var $payway = 4;

        function _init() {
            _queryPayWay();
            $(document).on("click", "#deposit-speedpay-submit", _doPay);
            $(document).on("click", "#deposit-speedpay-reset", _reset);
        }

        function _queryPayWay() {

            var payWayList = _deposit[$payway];
            var result = _buildHtml(4, "快捷支付", payWayList);

            $parent.find(".payway-list").html(result);
        }

        function _doPay() {

            var $activeBtn = $parent.find(".payWayBtn.active");

            var payway = $parent.find(".payWayBtn.active").data('payway');
            var platform = $parent.find(".payWayBtn.active").data('platform');

            if (payway == 4) {
                if (platform.indexOf("ydkj") > -1) {
                    var $bankcard = $("#ydkj-bankcard").val();

                    if ($bankcard == "" || typeof $bankcard == 'undefined') {
                        _showLayer("[提示]请输入银行卡号!");
                        return false;
                    }
                }
            }

            var formData = {
                usetype: 2,
                loginName: $('#j-loginname').val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr('data-id'),
                payUrl: $activeBtn.attr('data-url'),

                bankcard: $bankcard
                // bankname: $bankname,
                // phoneNumber: $phoneNumber,
                // bankCode: $bankname
            };

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);

            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");
                _payTo(formData);
            }
        }

        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();

    }

    /***
     * 京东支付
     */
    function _JDManage() {

        var $parent = $("#tab-jdpay");
        var $payway = 10;

        function _init() {
            _queryPayWay();
            $(document).on("click", "#deposit-jdpay-submit", _doPay);
            $(document).on("click", "#deposit-jdpay-reset", _reset);
        }

        function _queryPayWay() {
            $parent.find(".payway-list").html(_buildHtml(10, "京东支付", _deposit[$payway]));
        }

        function _doPay() {

            var $activeBtn = $parent.find(".payWayBtn.active");
            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var formData = {
                usetype: 2,
                loginName: $('#j-loginname').val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr('data-id'),
                payUrl: $activeBtn.attr('data-url')
            };

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);
            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");
                _payTo(formData);
            }
        }

        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();

    }

    /***
     * 银联支付
     */
    function _YinlianManage() {

        var $parent = $("#tab-yinlian");
        var $payway = 13;

        function _init() {
            _queryPayWay();
            $(document).on("click", "#deposit-yinlian-submit", _doPay);
            $(document).on("click", "#deposit-yinlian-reset", _reset);
        }

        function _queryPayWay() {
            $parent.find(".payway-list").html(_buildHtml(13, "银联支付", _deposit[$payway]));
        }

        function _doPay() {

            var $activeBtn = $parent.find(".payWayBtn.active");

            var formData = {
                usetype: 2,
                loginName: $('#j-loginname').val(),
                orderAmount: $parent.find('.money-input').val(),

                platformId: $activeBtn.attr('data-id'),
                payUrl: $activeBtn.attr('data-url')
            };

            var $max = $activeBtn.data("max");
            var $min = $activeBtn.data("min");

            var $placeholder = "";
            if ($max > 9999) {
                $placeholder = "金额" + $min + "元到" + $max / 10000 + "万";
            } else {
                $placeholder = "金额" + $min + "元到" + $max + "元";
            }

            var msg = _payMoneyCheck(formData);
            if (formData.orderAmount < $min || formData.orderAmount > $max) {
                msg = $placeholder;
                $parent.find(".ui-message").text(msg).removeClass("ui-hidden");
            } else {
                $parent.find(".ui-message").text("").addClass("ui-hidden");
                _payTo(formData);
            }
        }


        function _reset() {
            $parent.find("input[type=button]").removeClass("active");
            $parent.find("input[type=text]").val("");
        }

        _init();

    }

    function _payTo(formData) {

        var err = _payDataCheck(formData);
        if (err) {
            _showLayer(err);
            return;
        }

        if (_TEST_MODE == 1) {
            console.log(formData)
        } else {
            if (_SUBMIT_FLAG == false) {
                _formSubmit(formData);
                $(".ui-submit").addClass("disabled").attr("disabled", true);
                _SUBMIT_FLAG = true;

                setTimeout(function () {
                    $(".ui-submit").removeClass("disabled").removeAttr("disabled");
                    _SUBMIT_FLAG = false;
                }, 1000);
            } else {
                // _showLayer("请勿重复提交订单!");
            }
        }
    }

    function _formSubmit(formData) {
        try {
            var inputs = '';
            for (var name in formData) {
                inputs += String.format(_inputHtml, name, formData[name]);
            }
            var $form = $(String.format(_formHtml, _payUrl, inputs));
            $('body').append($form);
            $form.submit();
        } catch (e) {
        }

        setTimeout(function () {
            $form.remove();
            inputs = $form = null;
        }, 1000);
    }

    //在線支付資料驗證
    function _payMoneyCheck(formData) {

        var result = "";

        if (!formData.orderAmount) {
            result = "金额不可为空";
        }
        if (isNaN(formData.orderAmount)) {
            result = "金额只能为数字";
        }

        return result;
    }

    //在線支付資料驗證
    function _payDataCheck(formData) {
        if (!formData.platformId) {
            return "[提示]支付方式不可为空";
        }
        if (!formData.loginName) {
            return "[提示]帳號不可为空";
        }
        return false;
    }

    function _doMoneyList(e) {

        var $that = $(e);
        var $parent = $that.parents(".tab-panel.active").find(".tab-panel.active");

        $that.addClass('active').siblings().removeClass('active');
        $parent.find(".money-error-message").addClass("hidden");

        var $activeBtn = $parent.find('.payWayBtn.active');

        var $platform = $activeBtn.data("platform");
        var $payway = $activeBtn.data("payway");
        var $max = $activeBtn.data("max");
        var $min = $activeBtn.data("min");

        if (!isEmpty($max) && !isEmpty($min)) {
            var $placeholder = $min + "-" + $max;
            $parent.find('.money-input').attr("placeholder", $placeholder);
        }

        var arr = "", fixAmountList = "";
        if (!isEmpty($payway) && !isEmpty($platform)) {
            fixAmountList = _getFixAmount($payway, $platform);
        }

        // 通道金额
        if (fixAmountList && fixAmountList.length > 0) {
            arr = fixAmountList;
            $parent.find(".money-input").parent(".deposit-common-lists").hide();
        } else {
            arr = [10, 20, 30, 50, 100, 200, 300, 400, 500, 1000, 2000, 3000, 5000, 10000];
            $parent.find(".money-input").parent(".deposit-common-lists").show();
        }

        var _html = _doMoneyBtn(arr, $max, $min);

        $parent.find(".money-list").html(_html);
    }

    function _getFixAmount($payway, $platform) {

        var result = "";
        var arr = _deposit[$payway];

        for (var i = 0; i < arr.length; i++) {
            var o = arr[i];
            if (o.payPlatform == $platform) {
                if (o.fixAmount) {
                    result = o.fixAmount;
                    break;
                }
            }
        }

        if (result != "") {
            result = result.split(",");
        }

        return result;
    }

    function _doMoneyBtn(arr, max, min) {

        if (!arr || arr.length < 1) {
            return false;
        }

        var _html = "";

        for (var i = 0; i < arr.length; i++) {
            var _num = arr[i];

            if (_num <= max && _num >= min) {
                _html += '<input type="button" class="money-btn" data-value="' + _num + '" value="' + _num + '">'
            }
        }

        return _html;
    }

    // 点击事件
    function clickEvent() {

        /**
         * 隐藏弹窗
         */
        $(document).on("click", ".layui-layer-shade", function () {
            if ($("body").hasClass("layer-open")) {
                _hideLayer();
            }
        });

        /**
         * 按钮状态
         */
        $(document).on("click", ".money-btn", function () {
            var $that = $(this);
            $that.addClass('active').siblings().removeClass("active");
        });

        $(document).on("click", ".payWayBtn", function () {

            $(".money-input").val("");

            var $that = $(this);
            var $max = "", $min = "", $fee = "", _moneyLimit = "";
            $that.addClass('active').siblings().removeClass("active");

            var $panel = $that.parents(".tab-cont.active").find(".tab-panel.active");

            var payUrl = $that.data("url");
            var payway = $that.data("payway");
            var platform = $that.data("platform");

            _doMoneyList($that);

            $max = $that.data("max");
            $min = $that.data("min");
            $fee = $that.data("fee");

            if ($fee == "" || $fee == "0%" || typeof $fee == 'undefined') {
                $panel.find(".fee").text("免手续费");
            } else {
                $panel.find(".fee").text($fee + "%");
            }

            if ($max != "" && $min != "") {

                if ($max > 9999) {
                    _moneyLimit = "金额" + $min + "元到" + $max / 10000 + "万";
                } else {
                    _moneyLimit = "金额" + $min + "元到" + $max + "元";
                }

                $panel.find(".max").text($max);
                $panel.find(".min").text($min);
                $panel.find(".money-input").attr("placeholder", _moneyLimit);
                $panel.find(".ui-message").text(_moneyLimit).addClass("ui-hidden");

                var $moneyBtn = $panel.find('.money-list .money-btn');

                var _arr = "";

                if (payUrl == "/zxf/zfb_wx") {
                    platform = "zxfwx";
                    $("#wexin-wexinQR-special-list").show();
                    $("#wexin-wexinQR-list").hide();
                } else {
                    $("#wexin-wexinQR-special-list").hide();
                    $("#wexin-wexinQR-list").show();
                }

                $moneyBtn.show().filter(function () {
                    var val = $(this).val();

                    if (typeof _arr == "undefined" || !_arr) {
                        return (val > $max || val < $min);
                    } else {
                        return (_arr.indexOf(Number(val)) == -1 || val > $max || val < $min);
                    }
                }).hide();

            }


            if (payway == 4) {

                if (platform.indexOf("ydkj") > -1) {
                    $(".ydkj").removeClass("ui-hidden");
                } else {
                    $(".ydkj").addClass("ui-hidden");
                }
            }

            if (payway == 1) {
                if (platform.indexOf("zfzfb") > -1) {
                    $(".j-zfzfb").removeClass("ui-hidden");
                } else {
                    $(".j-zfzfb").addClass("ui-hidden");
                }
            }

            if (payUrl == "/kht/zfb_wx" || payUrl == "/qsx/zfb_wx" || payUrl == "/nhh/zfb_wx") {
                $("#j-bank-choose").hide();
            } else {
                $("#j-bank-choose").show();
            }

            calcFee()
        });


//支付宝支付

        $('.js-zfb-payway').on('click', '.payWayBtn', function () {
            var zfbUrl = $(this).data('url'),
                zfbMax = $(this).data('max'),
                zfbMin = $(this).data('min');

            var zfbHtml = '';
            if (zfbUrl != undefined) {

                // /xm/zfb_wx  /hh/scp /hf/scp /zt/scp /vp/zfb /xlbn/scan
                switch (zfbUrl) {
                    case"/xm/zfb_wx":
                        zfbHtml = '800-5000&100-700';
                        break;
                    case"/hh/scp":
                        zfbHtml = '800-5000&100-700';
                        break;
                    case"/hf/scp":
                        zfbHtml = '900-3000&401-800';
                        break;
                    case"/hxf/scp":
                        zfbHtml = '800-3000&200-700&30-100';
                        break;
                    case"/zt/scp":
                        zfbHtml = '800-2000&200-700&50-100';
                        break;
                    case"/vp/zfb":
                        zfbHtml = '900-3000&90-800&10-80';
                        break;
                    case"/xlbn/scan":
                        zfbHtml = '800-3000&200-700&30-100';
                        break;
                    case"/sy/scan":
                        zfbHtml = '600-3000&80-500&10-70';
                        break;
                    case"/payTong/zfb_wx":
                        zfbHtml = '800-5000&100-700';
                    default:
                        zfbHtml = zfbMin + '-' + (zfbMax / 2) + '&' + (parseFloat((zfbMax + 100) / 2)) + '-' + zfbMax;
                }

                var zfbArr = zfbHtml.split('&'),
                    intrvalHtml = '';

                for (var i = 0; i < zfbArr.length; i++) {
                    intrvalHtml += '<li>' + zfbArr[i] + '</li>'
                }

                $('.js-interval-tab').html(intrvalHtml);

                $('.js-interval-tab li').eq(0).trigger('click');
            }

        });

//支付宝推荐金额选择
        $('.js-interval-tab').on('click', 'li', function () {

            var zhtml = $(this).html();

            var arr2 = zhtml.split('-');

            var zfbHtml = '';

            var vpArray = ["5", "10", "20", "30", "40", "50", "60", "70", "80", "90", "100", "200", "300", "500", "1000", "2000", "3000", "5000", "8000", "10000", "15000"];

            for (var i = 0; i < vpArray.length; i++) {
                if (vpArray[i] >= parseFloat(arr2[0]) && vpArray[i] <= parseFloat(arr2[1])) {

                    zfbHtml += '<input type="button" class="money-btn" data-value="' + vpArray[i] + '" value="' + vpArray[i] + '">';
                }
            }

            $('.js-tag-container').html(zfbHtml);

            $(this).addClass('active').siblings('.active').removeClass('active');

        });


        $(document).on("keyup", ".money-input", function () {
            var value = $(this).val();
            value = value.replace(/[^\d]/g, '');

            if (value > 0) {
                $(this).val(value);
                calcFee();
            }
        });

        $(document).on("change", "#quick-save-type", function () {
            var value = $(this).val();
            var text = $(this).find("option:selected").text();


            if (text == "网上银行转账") {
                $("#j-quickpayway-bank").show();
            } else {
                $("#j-quickpayway-bank").hide();
            }

            if (text == "支付宝转账") {
                $(".j-quickpayway-zfb").show();
            } else {
                $(".j-quickpayway-zfb").hide();
            }
        });


        /**
         * 点金额放进输入诓，以及点其他金额时
         */
        $(document).on("click", ".money-list .money-btn", function () {
            var $that = $(this);
            var value = $that.val();

            if (value != "" && value != 0) {
                $that.parents(".tab-panel").find(".money-input").val(value);
                calcFee();
            }

        });


        /**
         * 输入金额时
         */
        $(document).on("click", ".money-input", function () {
            var $that = $(this);
            $that.val("");
            $that.parents(".tab-panel").find(".money-btn").removeClass('active');

            calcFee();
        });

        /**
         * 点tab对应tag
         */
        $(document).on("click", ".deposit-menu-list li", function () {
            var $that = $(this);
            var $href = $that.attr("href");

            $($href).find(".payment-item:visible").eq(0).trigger("click");
            $($href).find(".payWayBtn:eq(0)").trigger("click");
        });

        $(document).on("click", ".payment-lists li", function () {
            var $href = $("#tab-deposit").find(".tab-cont.active").find(".payment-item.active").attr("href");
            $($href).find(".payWayBtn:eq(0)").trigger("click");
        });
    }
}


/**
 * 实际到账
 */

function calcFee() {

    var $panel = $("#tab-deposit > .tab-panel.active").find(".tab-panel.active");

    var $activeBtn = $panel.find(".payWayBtn.active");
    var fee = $activeBtn.data("fee");

    var value = $.trim($panel.find(".money-input").val());

    if (value != 0) {
        value = parseFloat(value);
    }

    if (!fee) {
        fee = 0;
    }

    if (!isNaN(value) && value != "") {

        var pay = value - parseFloat((value / 100) * fee);

        if (isFloat(pay)) {
            pay = pay.toFixed(2);
        }

        $panel.find(".money-pay").html(pay + '元');

    } else {
        $panel.find(".money-pay").html("0元");
    }

}

function isFloat(x) {
    return !!(x % 1);
}


function _loadLayer() {
    layer.load(2, {shade: [0.7, '#000'], offset: ['46%', '45%']});
}

function _showLayer(msg, btn) {

    if (btn == "") {
        btn = '关闭';
    }

    layer.open({
        skin: 'tips-layer',
        closeBtn: false,
        content: msg,
        btn: btn
    });

    $("body").addClass("layer-open");
}

function _hideLayer() {
    // closeProgressBar();
    layer.closeAll();
    $("body").removeClass("layer-open")
}

function addBlank(v) {
    var result = "";
    v = v.split("");
    for (var i = 0; i < v.length; i++) {
        if (i % 4 == 0) {
            result += " ";
        }
        result += v[i];
    }

    return result;
}


String.format = function () {
    var theString = arguments[0];

    for (var i = 1; i < arguments.length; i++) {
        var regEx = new RegExp("\\{" + (i - 1) + "\\}", "gm");
        theString = theString.replace(regEx, arguments[i]);
    }
    return theString;
};

// <%--复制--%>
var clipboard = new Clipboard('.btn-copy');
clipboard.on('success', function (e) {
    _showLayer('复制成功');
});

function arrToStr(arr) {

    var result = "";

    if (!Array.isArray(arr)) {
        return false;
    }

    result += '此存款通道支持';
    for (var i = 0; i < arr.length; i++) {
        if (i == 0) {
            result += arr[i];
        } else {
            result += "、" + arr[i];
        }
    }

    return result;
}

function isEmpty(val) {
    return !$.trim(val)
}