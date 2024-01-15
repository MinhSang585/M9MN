/**
 * 第三方支付管理
 */
function TPPManage(base_url) {
    var that = this;
    var _base_url = base_url;

    var _testMode = 0;
    var _payWay = "";

    if (_testMode == 1) {
        _payWay = _base_url + 'data/deposit/payWay98.json?v=2';
    } else {
        _payWay = _base_url + 'asp/pay_way.php';
    }

    var _payUrl2 = _base_url + 'asp/pay_api.php';

    var _payUrl = _base_url + 'mobi/thirdPartyPayment.php';
    var _payDatasUrl = _base_url + 'mobi/getWorkThirdPartyPayments.php';
    var _payWeixinUrl = _base_url + 'mobi/getWorkWeixin.php';
    var _payDCardUrl = _base_url + 'mobi/dCardPay.php';
    var _getDCardPayWorkUrl = _base_url + 'mobi/getDCardPayWork.php';
    var _getAllPaymentsUrl = _base_url + 'mobi/getAllPayments.php';
    var _payZfbqr2Url = _base_url + 'mobi/getWorkZfbqr2.php';

    var _bfbBankDataUrl = _base_url + 'mobile/json/bank/bfb.json';
    var _czf1BankDataUrl = _base_url + 'mobile/json/bank/czf1.json';
    var _czf2BankDataUrl = _base_url + 'mobile/json/bank/czf2.json';
    var _zf1BankDataUrl = _base_url + 'mobile/json/bank/zf1.json';
    var _zf2BankDataUrl = _base_url + 'mobile/json/bank/zf2.json';
    var _zf3BankDataUrl = _base_url + 'mobile/json/bank/zf3.json';
    var _zfdkBankDataUrl = _base_url + 'mobile/json/bank/zfdk.json';
    var _zfbBankDataUrl = _base_url + 'mobile/json/bank/zfb.json';
    var _hfBankDataUrl = _base_url + 'mobile/json/bank/hf.json';
    var _hcBankDataUrl = _base_url + 'mobile/json/bank/hc.json';
    var _ipsBankDataUrl = _base_url + 'mobile/json/bank/ips.json';
    var _gfbBankDataUrl = _base_url + 'mobile/json/bank/gfb.json';
    var _zfwxBankDataUrl = _base_url + 'mobile/json/bank/zfwx.json';
    var _commonHaierBankDataUrl = _base_url + 'mobile/json/bank/commonHaier.json';
    var _xlbwyBankDataUrl = _base_url + 'mobile/json/bank/xlbwy.json';
    var _hcymdBankDataUrl = _base_url + 'mobile/json/bank/hc.json';
    var _xbwyBankDataUrl = _base_url + 'mobile/json/bank/xbwy.json';

    var _formHtml = '<form action="{0}" method="post" target="_self">{1}</form>';
//	var _formHtml = '<form action="{0}" method="post" target="_blank">{1}</form>';
    var _inputHtml = '<input type="hidden" name="{0}" value="{1}"/>';

    that.formSubmit = _formSubmit;

    that.payWay = function (callback) {
        mobileManage.ajax({
            url: _payWay,
            timeout: 10000,
            callback: callback
        });
    };

    that.payTo = function (formData) {

        var err = _payDataCheck2(formData);
        if (err) {
            alert(err);
            return;
        }

        mobileManage.getLoader().open('处理中');
        try {
            var inputs = '';
            for (var name in formData) {
                inputs += String.format(_inputHtml, name, formData[name]);
            }
            $form = $(String.format(_formHtml, _payUrl2, inputs));
            $('body').append($form);
            $form.submit();

        } catch (e) {
            mobileManage.getLoader().close();
        }

        setTimeout(function () {
            $form.remove();
            inputs = $form = null;
        }, 1000);

        // mobileManage.ajax({
        //     url:_payUrl2,
        //     param:formData,
        //     callback:function (result) {
        //
        //     }
        // });
    };


    /**
     * 第三方在线支付
     * @param {Object} 提交资料
     * @param {Function} 回调
     */
    that.pay = function (formData, callback) {
        var _formData = {
            payId: '',
            money: '',
            bankName: ''
        };
        $.extend(_formData, formData);

        //檢查
        var err = _payDataCheck(_formData);
        if (err) {
            if (typeof callback === 'function') {
                callback({success: false, message: err});
            }
            return;
        }
        mobileManage.ajax({
            url: _payUrl,
            param: _formData,
            callback: function (result) {
                if (result.success) {
                    if ("http://www.glubanyunshang.com/ybpay.jsp" == result.data.url) {  //银宝支付宝
                        result.data.url = CreateYBZFBURL(result.data.url, result.data.formData);
                    }
                    if ("http://apika.10001000.com/chargebank.aspx" == result.data.url) {  //千网支付宝 and 网银
                        result.data.url = CreateQWZFBURL(result.data.url, result.data.formData);
                    }
                    /*if("http://www.fushengqian.com/kdwx.jsp"==result.data.url){  //口袋微信支付4
                     result.data.url = CreateKDWXURL(result.data.url,result.data.formData);
                     }*/

                    _formSubmit(result.data.url, result.data.formData);
                    callback(result);
                } else {
                    callback(result);
                }
            }
        });
    };

    function CreateYBZFBURL(YBZFBurl, formData) {
        return YBZFBurl + "?partner=" + formData.partner + "&banktype=" + formData.banktype + "&paymoney=" + formData.paymoney + "&ordernumber=" + formData.ordernumber + "&callbackurl=" + formData.callbackurl + "&hrefbackurl=" + formData.hrefbackurl + "&attach=" + formData.attach + "&sign=" + formData.sign;
    }

    function CreateQWZFBURL(QWZFBurl, formData) {
        return QWZFBurl + "?parter=" + formData.parter + "&type=" + formData.type + "&orderid=" + formData.orderid + "&callbackurl=" + formData.callbackurl + "&hrefbackurl=" + formData.hrefbackurl + "&value=" + formData.value + "&attach=" + formData.attach + "&payerIp=" + formData.payerIp + "&sign=" + formData.sign;
    }

    //口袋微信支付4
    function CreateKDWXURL(KDWXurl, formData) {
        return KDWXurl + "?P_UserId=" + formData.P_UserId + "&P_OrderId=" + formData.P_OrderId + "&P_FaceValue=" + formData.P_FaceValue + "&P_ChannelId=" + formData.P_ChannelId + "&P_Price=" + formData.P_Price + "&P_Quantity=" + formData.P_Quantity + "&P_Description=" + formData.P_Description + "&P_Notic=" + formData.P_Notic + "&P_Notify_URL=" + formData.P_Notify_URL + "&P_PostKey=" + formData.P_PostKey;
    }

    /**
     * 点卡支付
     * @param {Object} formData 提交资料
     * @param {Function} callback 回调方法
     */
    that.dCardPay = function (formData, callback) {
        var _formData = {
            card_code: '',
            card_no: '',
            card_password: '',
            money: ''
        };
        $.extend(_formData, formData);

        //檢查
        var err = _dCardPayDataCheck(_formData);
        if (err) {
            callback({success: false, message: err});
            return;
        }
        mobileManage.ajax({
            url: _payDCardUrl,
            param: _formData,
            callback: function (result) {
                if (result.success) {
                    callback(result);
                } else {
                    callback(result);
                }
            }
        });
    };

    /**
     * 取得可使用的在線支付
     * @param {Function} 回调
     */
    that.queryPayDatas = function (callback) {
        mobileManage.ajax({
            url: _payDatasUrl,
            callback: callback
        });
    };


    /**
     * 取得可使用的点卡支付
     * @param {Function} callback 回调方法
     */
    that.getDCardPayWork = function (callback) {
        mobileManage.ajax({
            url: _getDCardPayWorkUrl,
            callback: callback
        });
    };

    /**
     * 取得可使用的微信支付
     * @param {Object} formData 提交资料
     * @param {Function} callback 回调方法
     */
    that.queryWeixin = function (callback) {
        mobileManage.ajax({
            url: _payWeixinUrl,
            callback: callback
        });
    };

    /**
     * 取得可使用的支付
     * @param {Object} formData 提交资料
     * @param {Function} callback 回调方法
     */
    that.getAllPayments = function (callback) {
        mobileManage.ajax({
            url: _getAllPaymentsUrl,
            callback: callback
        });
    };


    /**
     * 取得可使用的在线支付
     * @param {String} 在线支付类型
     * @param {Function} 回调
     */
    that.queryBankDataByPayId = function (id, callback) {
        var url = '';
        switch (id) {
            case '智付':
                url = _zf1BankDataUrl;
                break;
            case '智付2':
                url = _zf2BankDataUrl;
                break;
            case '智付3':
                url = _zf3BankDataUrl;
                break;
            case '智付点卡':
                url = _zfdkBankDataUrl;
                break;
            case '汇付':
                url = _hfBankDataUrl;
                break;
            case '汇潮':
                url = _hcBankDataUrl;
                break;
            case '环迅':
                url = _ipsBankDataUrl;
                break;
            case '汇付1':
                url = _hfBankDataUrl;
                break;
            case '汇付2':
                url = _hfBankDataUrl;
                break;
            case '汇付3':
                url = _hfBankDataUrl;
                break;
            case '汇付4':
                url = _hfBankDataUrl;
                break;
            case '汇付5':
                url = _hfBankDataUrl;
                break;
            case '汇付6':
                url = _hfBankDataUrl;
                break;
            case '汇付7':
                url = _hfBankDataUrl;
                break;
            case '币付宝':
                url = _bfbBankDataUrl;
                break;
            case '支付宝':
                url = _zfbBankDataUrl;
                break;
            case '通用智付1':
                url = _czf1BankDataUrl;
                break;
            case '通用智付2':
                url = _czf2BankDataUrl;
                break;
            case '国付宝1':
                url = _gfbBankDataUrl;
                break;
            case '智付微信':
                url = _zfwxBankDataUrl;
                break;
            case '海尔支付':
                url = _commonHaierBankDataUrl;
                break;
            case '迅联宝网银':
                url = _xlbwyBankDataUrl;
                break;
            case '汇潮网银':
                url = _hcymdBankDataUrl;
                break;
            case '新贝网银':
                url = _xbwyBankDataUrl;
                break;
            default:
                if (typeof callback === 'function') {
                    callback({success: false, message: '支付方式不存在！'});
                }
                return;
        }
        ;
        mobileManage.ajax({
            url: url,
            callback: callback
        });
    };

    //在線支付資料驗證
    function _payDataCheck2(formData) {
        if (!formData.platformId) {
            return "[提示]支付方式不可为空";
        }
        if (!formData.loginName) {
            return "[提示]帳號不可为空";
        }
        if (!formData.orderAmount) {
            return "[提示]金额不可为空";
        }
        if (isNaN(formData.orderAmount)) {
            return "[提示]金额只能为数字";
        }
        return false;
    }

    //在線支付資料驗證
    function _payDataCheck(formData) {
        if (!formData.payId) {
            return "[提示]支付方式不可为空";
        }
        if (!formData.bankName) {
            return "[提示]银行不可为空";
        }
        if (!formData.money) {
            return "[提示]金额不可为空";
        }
        if (isNaN(formData.money)) {
            return "[提示]金额只能为数字";
        }
        return false;
    }

    //点卡支付資料驗證
    function _dCardPayDataCheck(formData) {
        if (!formData.card_code) {
            return "[提示]点卡类型不可为空";
        }
        if (!formData.card_no) {
            return "[提示]点卡卡号不可为空";
        }
        if (!formData.card_password) {
            return "[提示]点卡密码不可为空";
        }
        if (!formData.money) {
            return "[提示]存款金额不可为空";
        }
        if (isNaN(formData.money)) {
            return "[提示]金额只能为数字";
        }
        return undefined;
    }

    //使用form submit 轉頁
    function _formSubmit(url, formData) {
        mobileManage.getLoader().open('跳转中');
        try {
            var inputs = '';
            for (var name in formData) {
                inputs += String.format(_inputHtml, name, formData[name]);
            }
            var $form = $(String.format(_formHtml, url, inputs));
            $('body').append($form);
            $form.submit();

        } catch (e) {
            mobileManage.getLoader().close();
        }

        setTimeout(function () {
            $form.remove();
            inputs = $form = null;
        }, 1000);
    }

    /**
     * 取得可使用的支付宝在线支付
     * @param {Object} formData 提交资料
     * @param {Function} callback 回调方法
     */
    that.queryZfbqr2 = function (callback) {
        mobileManage.ajax({
            url: _payZfbqr2Url,
            timeout: 10000,
            callback: callback
        });
    };
}