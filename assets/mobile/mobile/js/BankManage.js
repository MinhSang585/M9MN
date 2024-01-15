/**
 * 银行管理
 */

function BankManage(base_url) {
    var that = this;
    var _base_url = base_url;

    var _getBankDataByNameUrl = _base_url + 'mobi/bankData.php';
    var _isBindBankNoUrl = _base_url + 'mobi/isBindBankNo.php';
    var _bindBankNoUrl = _base_url + 'mobi/bindBankNo.php';
    var _withdrawlUrl = _base_url + "mobi/withdrawlNew.php";
    var _transferUrl = _base_url + "mobi/transfer.php";
    var _verifyDepositUrl = _base_url + "mobi/verifyDeposit.php";
    var _fastBankInfoUrl = _base_url + "mobi/getFastBankInfo.php";
    var _remarkUrl = _base_url + "mobi/getRemark.php";
    var _zfbBankInfoUrl = _base_url + "mobi/getZFBBankInfo.php";
    var _getZFBQRUrl = _base_url + "mobi/getAlipayAccount.php";
    var _bindZFBQRUrl = _base_url + "mobi/saveAlipayAccount.php";
    var _getWXValidateDepositInfo = _base_url + "mobi/getWXValidateDepositInfo.php";

    /**
     * 是否已經绑定银行卡号
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.isBindBankNo = function (callback) {
        mobileManage.ajax({
            url: _isBindBankNoUrl,
            callback: callback
        });
    };

    /**
     * 是绑定银行卡号
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.bindBankNo = function (formData, callback) {
        var _formData = {
            cardNo: '',
            bankName: '',
            addr: '',
            password: '',
            bindingCode: ''
        };
        $.extend(_formData, formData);
        mobileManage.ajax({
            url: _bindBankNoUrl,
            param: _formData,
            callback: callback
        });
    };

    /**
     * 依银行名称取得卡号跟分行
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.getBankDataByName = function (formData, callback) {
        var _formData = {
            bankName: ''
        };
        $.extend(_formData, formData);
        mobileManage.ajax({
            url: _getBankDataByNameUrl,
            param: _formData,
            callback: callback
        });
    };


    /**
     * 取得支付宝扫马帐号
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.getZFBQR = function (callback) {
        mobileManage.ajax({
            url: _getZFBQRUrl,
            callback: callback
        });
    };

    /**
     * 绑定支付宝扫码帐号
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.bindZFBQR = function (formData, callback) {
        var _formData = {
            password: false,
            account: false,
        };
        $.extend(_formData, formData);
        //检查
        var err = _bindZFBQRDataCheck(_formData);
        if (err) {
            if (typeof callback === 'function') {
                callback({success: false, message: err});
            }
            return;
        }
        mobileManage.ajax({
            url: _bindZFBQRUrl,
            param: _formData,
            callback: callback
        });
    };


    /**
     * 取得极速转帐账户资料
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.getFastBankInfo = function (formData, callback) {
        mobileManage.ajax({
            url: _fastBankInfoUrl,
            param: formData,
            callback: callback
        });
    };

    /**
     * 取得支付宝账户资料
     * @param {Function} callback 回调方法
     */
    that.getZFBBankInfo = function (callback) {
        mobileManage.ajax({
            url: _zfbBankInfoUrl,
            callback: callback
        });
    };

    /**
     * 取得附言
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.getRemark = function (formData, callback) {
        var _formData = {
            name: '',
            bankName: '',
            imageCode: ''
        };
        $.extend(_formData, formData);

        mobileManage.ajax({
            url: _remarkUrl,
            param: _formData,
            callback: callback
        });
    };


    /**
     * 存款
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.deposit = function (formData, callback) {
        var _formData = {
            type: ''
        };
        $.extend(_formData, formData);
        if (_formData.type == 'fast') {
            _speedTransfer(formData, callback);
        } else if (_formData.type == 'atm') {
            _validateDeposit(formData, callback);
        } else {
            callback({success: false, message: '该方式不存在！'});
        }

    };

    /**
     * 额度验证存款
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    function _validateDeposit(formData, callback) {
        var _formData = {
            money: ''
        };
        $.extend(_formData, formData);
        //检查
        var err = _validateDepositDataCheck(_formData);
        if (err) {
            if (typeof callback === 'function') {
                callback({success: false, message: err});
            }
            return;
        }
        mobileManage.ajax({
            url: _verifyDepositUrl,
            param: _formData,
            callback: callback
        });
    };

    /**
     * 提款
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.withdrawal = function (formData, callback) {
        var _formData = {
            name: '',
            password: '',
            bankName: '',
            cardNo: '',
            addr: '',
            money: '',
            withdrawlType: ''
        };
        $.extend(_formData, formData);
        //检查
        var err = _withdrawlDataCheck(_formData);
        if (err) {
            if (typeof callback === 'function') {
                callback({success: false, message: err});
            }
            return;
        }
        mobileManage.getModel().open('withdrawalConfirm', [{
            title: '请确认收款人资料',
            name: _formData.name,
            bankName: _formData.bankName,
            bankAccount: _formData.cardNo,
            withdrawalMoney: _formData.money,
            withdrawlType:_formData.withdrawlType,
            callback: function (confirm) {
                if (confirm) {
                    mobileManage.getLoader().open('处理中');
                    mobileManage.ajax({
                        url: _withdrawlUrl,
                        param: _formData,
                        callback: callback
                    });
                }
            }
        }]);
    };

    /**
     * 转账
     * @param {Object} formData 提交参数
     * @param {Function} callback 回调方法
     */
    that.transfer = function (formData, callback) {
        var _formData = {
            transferGameOut: '',
            transferGameIn: '',
            money: ''
        };
        $.extend(_formData, formData);
        //检查
        var err = _transferDataCheck(_formData);
        if (err) {
            callback({success: false, message: err});
            return;
        }
        mobileManage.ajax({
            url: _transferUrl,
            param: _formData,
            callback: callback
        });
    };


    //验证提款资料
    function _withdrawlDataCheck(formData) {

        if (!formData.bankName) {
            return "[提示]请选择卡折种类！";
        }
        if (!formData.cardNo) {
            return "[提示]卡折号不可为空！";
        }

        if (!formData.addr) {
            return "[提示]开户网点不可为空！";
        }
        if (!formData.money) {
            return "[提示]提款金额不可为空！";
        }
        if (formData.money < 100) {
            return "[提示]单次提款金额最低100";
        }
        if (Math.floor(formData.money) != formData.money) {
            return "[提示]提款金额需为整数！";
        }
        return false;
    }

    //验证转账资料
    function _transferDataCheck(formData) {
        if (!formData.money) {
            return "[提示]转账金额不可为空！";
        }
        if (!formData.transferGameOut) {
            return "[提示]请选择来源账号！";
        }
        if (!formData.transferGameIn) {
            return "[提示]请选择目标账号！";
        }
        if (isNaN(formData.money)) {
            return "[提示]金额只能为数字";
        }
        return false;
    }

    //额度验证存款
    function _validateDepositDataCheck(formData) {
        if (!formData.money) {
            return "[提示]金额不可为空！";
        }
        if (isNaN(formData.money)) {
            return "[提示]金额只能为数字";
        }
        return false;
    }

    //绑定支付宝扫码帐户
    function _bindZFBQRDataCheck(formData) {
        if (!formData.password) {
            return "[提示]游戏帐户密码不可为空！";
        }
        if (!formData.account) {
            return "[提示]支付宝帐户不可为空！";
        }
        return false;
    }

    //取得额度验证存款资讯
    that.getWXValidatePayInfo = function (callback) {
        mobileManage.ajax({
            url: _getWXValidateDepositInfo,
            callback: callback
        });
    };

}