/**
 * 依赖MUI 设计的Model
 */

function MUIModel(manage, muiObj) {
    if (typeof manage !== 'object') {
        alert('mobileManage error');
        return;
    }
    if (typeof muiObj !== 'object') {
        alert('mui error');
        return;
    }

    var that = this;
    //当前的model
    var _$currentModel = false;
    var _$currentId = false;

    //动作对应方法
    var _actionFn = {
        // 'login':_getLogin,
        // 'logout':_getLogout,
        'makeCall': _getMakeCall,
        'modifyPassword': _getModifyPassword,
        'question': _getQuestion,
        'news': _getNews,
        'download': _getDownload,
        'bankBind': _getBankBind,
        'unBankBind': _getUnBankBind,
        'confirm': _getConfirm,
        'goGame': _getGoGame,
        'goOrDownload': _getGoOrDownloadGame,
        'goGameOrFunGame': _getGoGameOrFunGame,
        'zfbBind': _getZfbBind,
        'withdrawalConfirm': _withdrawalConfirm,
        'tips': _getTips,
        'historyBank': _getHistoryBank
    };

    // var _$loginModel = false;
    // var _$logoutModel = false;
    var _$makeCallModel = false;
    var _$modifyPasswordModel = false;
    var _$questionModel = false;
    var _$newsModel = false;
    var _$downloadModel = false;
    var _$bankBindModel = false;
    var _$unBankBindModel = false;
    var _$goGameModel = false;
    var _$goOrDownloadGameModel = false;
    var _$zfbDepositBindModel = false;
    var _$confirmModel = false;
    var _$goGameOrFunGameModel = false;
    var _$withdrawalConfirmModel = false;
    var _$tipsModel = false;
    var _$historyBankModal = false;

    //视窗改变时，去变更top位置
    $(window).resize(function () {
        if (_$currentModel) {
            _checkHeight(_$currentModel);
        }
    });

    //登入 html
    // var _loginModelHtml = [
    // 		'<div id="mui-login-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
    //   		'<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-arrow221">会员登入</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
    // 		'<div class="mui-panel">',
    // 			'<div class="mui-error-message"></div>',
    // 		  	'<div class="mui-textfield mui-textfield--float-label">',
    // 		    	'<input id="mui-login-account" type="text" required>',
    // 		    	'<label>账号</label>',
    // 		    	'<div class="message"></div>',
    // 		 	'</div>',
    // 		  	'<div class="mui-textfield mui-textfield--float-label">',
    // 		  	  	'<input id="mui-login-password" type="password" required>',
    // 		    	'<label>密码</label>',
    // 		  	'</div>',
    // 		  	'<div class="mui-textfield mui-textfield--float-label">',
    // 		  	  	'<input id="mui-login-code" type="text" required>',
    // 		    	'<label>验证码</label>',
    // 		    	'<img id="mui-login-img" title="如果看不清验证码，请点图片刷新" />',
    // 		    	'<div class="message">如果看不清验证码，请点图片刷新</div>',
    // 		  	'</div>',
    // 		  	'<div class="mui-btn mui-btn--raised mui-btn--pink small right" onclick="mobileManage.redirect(\'forgotPassword\');">忘记密码</div>',
    // 		  	'<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-login-submit">登入</div>',
    // 		'</div>',
    // 	'</div>'
    // ].join('');

    //登出 html
    // var _logoutModelHtml = [
    // 	'<div id="mui-logout-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
    // 	'<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-logout13">会员登出</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
    //  		'<div class="mui-panel">',
    //  			'<div class="mui-overlay-message">确定要登出？</div>',
    //  		  	'<div class="mui-btn mui-btn--raised mui-btn--pink right" id="mui-logout-cancel">取消</div>',
    //  		  	'<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-logout-submit">确定</div>',
    //  		'</div>',
    //  	'</div>'
    // ].join('');

    //修改密码 html
    // var _modifyPasswordModelHtml = [
    //     '<div id="mui-modifyPassword-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
    //     '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-refresh57">修改密码</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
    //     '<div class="mui-panel">',
    //     '<div class="mui-error-message"></div>',
    //     '<div class="mui-textfield mui-textfield--float-label">',
    //     '<input id="mui-modifyPassword-password" type="password" required>',
    //     '<label>旧密码</label>',
    //     '<div class="message"></div>',
    //     '</div>',
    //     '<div class="mui-textfield mui-textfield--float-label">',
    //     '<input id="mui-modifyPassword-newPassword" type="password" required>',
    //     '<label>新密码</label>',
    //     '<div class="message">密码为6-16位数字或英文字母，英文字母开头且不能和账号相同</div>',
    //     '</div>',
    //     '<div class="mui-textfield mui-textfield--float-label">',
    //     '<input id="mui-modifyPassword-confirmPassword" type="password" required>',
    //     '<label>确认密码</label>',
    //     '<div class="message">再次输入密码，确认新密码无误</div>',
    //     '</div>',
    //     '<div class="mui-btn mui-btn--raised mui-btn--pink right" id="mui-modifyPassword-cancel">取消</div>',
    //     '<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-modifyPassword-submit">修改</div>',
    //     '</div>',
    //     '</div>'
    // ].join('');


    //电话回播 html
    var _makeCallModelHtml = [
        '<div id="mui-makeCall-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-phone41">电话回播</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-overlay-message"></div>',
        '<div class="mui-textfield">',
        '<label style="position: static;margin-bottom: 5px;">您的联系号码</label>',
        '<input id="mui-makeCall-phone" type="text" placeholder="这里输入联系号码" style="border: 1px solid #ddd;margin: 5px 0">',
        '<div class="message">请输入您的注册电话或者您在使用的最新电话</div>',
        '</div>',
        '<div class="mui-textfield">',
        '<label style="position: static;margin-bottom: 5px;">验证码</label>',
        '<input id="getImgTryCode" type="text" placeholder="这里输入验证码"  style="border: 1px solid #ddd;margin: 5px 0">',
        '<img id="imgTryCode" style="position: absolute;top: 21px;right:1px;height:28px" src="/asp/agTryValidateCodeForIndex.php">',
        '<div class="message">请输入您的验证码</div>',
        '</div>',
        '<div class="mui-overlay-message"></div>',
        '<div class="btn btn-reset btn-mgb" id="mui-makeCall-cancel">取消</div>',
        '<div class="btn  btn-mg" id="mui-makeCall-submit">电话回播</div>',
        '</div>',
        '</div>'
    ].join('');


    //密保问题 html
    var _questionModelHtml = [
        '<div id="mui-question-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-closed">设定密保</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-error-message"></div>',
        '<div class="mui-select">',
        '<p>密保问题</p>',
        '<select id="mui-question-question"></select>',
        '</div>',
        '<div class="mui-textfield mui-textfield--float-label">',
        '<p>你的回答</p>',
        '<input id="mui-question-answer" type="text" required>',
        '</div>',
        '<div class="mui-textfield mui-textfield--float-label">',
        '<p>登入密码</p>',
        '<input id="mui-question-password" type="password" required>',
        '</div>',
        '<div class="btn" id="mui-question-submit">设定</div>',
        '<div class="btn btn-reset btn-mgb" id="mui-question-cancel">取消</div>',
        '</div>',
        '</div>'
    ].join('');

    //新闻动态 html
    var _newsModelHtml = [
        '<div id="mui-news-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-news-title" class="mui-overlay-title-text flaticon-speechbubble96"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-news-message" class="mui-overlay-message"></div>',
        '<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-news-next">下一条</div>',
        '<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-news-last">上一条</div>',
        '</div>',
        '</div>'
    ].join('');

    //檔案下载 html
    var _downloadModelHtml = [
        '<div id="mui-download-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-download-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-download-message" class="mui-overlay-message"></div>',
        '<div class="btn btn-reset btn-mg btn-mgb" id="mui-download-cancel">取消</div>',
        '<div class="btn btn-mg" id="mui-download-submit">继续下载</div>',
        '</div>',
        '</div>'
    ].join('');

    //进入游戏或下载游戏客户端 html
    var _goGameOrdownloadModelHtml = [
        '<div id="mui-goOrDownload-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-goOrDownload-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-goOrDownload-message" class="mui-overlay-message"></div>',
        '<div class="mui-buttons">',
        '	<div class="mui-btn mui-btn--raised mui-btn--orange" id="mui-goGame-submit">网页游戏</div>',
        '	<div class="mui-btn mui-btn--raised mui-btn--orange" id="mui-download-submit">下载客户端</div>',
        '</div>',
        '</div>',
        '</div>'
    ].join('');


    //银行卡/支付宝绑定 html
    var _bankBindModelHtml = [
        '<div id="mui-bankBind-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-closed">卡/折号绑定</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-textfield">',
        '<p class="funds-title">姓名</p>',
        '<input id="mui-bankBind-bdXinMing"  type="text" required >',

        '</div>',
        '<div class="mui-textfield">',
        '<p class="funds-title">银行卡号</p>',
        '<input id="mui-bankBind-cardNo" type="text" required placeholder="请输入正确的银行卡号，以免影响提款">',

        '</div>',
        '<div class="mui-textfield">',
        '<p class="funds-title">登入密码</p>',
        '<input id="mui-bankBind-password" type="password" required placeholder="您的登入密码">',

        '</div>',
        '<div class="mui-select">',
        '<p class="funds-title">银行名称</p>',
        '<input id="mui-bankBind-bankName" readonly placeholder="输入银行卡号自动识别">',

        '</div>',

        // '<div class="mui-message"><font color="red" style="font-size:80%;">您的银行卡姓名</font></div>',
        '<div class="mui-textfield mui-textfield--float-label">',
        '<p>验证码</p>',
        '<input id="mui-bankBind-bindingCode" type="text" required>',

        '</div>',
        '<div id="mui-bankBind-buttons" class="mui-buttons center">',
        '<div class="mui-message">支付宝账户需透过语音/短信验证取得验证码才可绑定！</div>',
        '<div class="mui-btn mui-btn--raised mui-btn--pink small" id="mui-bankBind-voice">语音验证</div>',
        '<div class="mui-btn mui-btn--raised mui-btn--pink small" id="mui-bankBind-sms">短信验证</div>',
        '</div>',

        '<div class="btn btn-reset" id="mui-bankBind-cancel">取消</div>',
        '<div class="btn  btn-mg" id="mui-bankBind-submit">提交</div>',
        '<div class="mui-message"><font color="red">温馨提示：<br/>1.请您绑定正确的银行卡，以免影响您的提款。<br/>2.请您输入对应银行卡正确的姓名以免影响您的提款。</font></div>',
        '</div>',

        '</div>'
    ].join('');

    //银行卡解除绑定 html
    var _unBankBindModelHtml = [
        '<div id="mui-bankBind-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-closed">解除银行卡绑定</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="space-1"></div>',
        '<div class="mui-textfield">',
        '<input id="mui-unBankBind-cardNo" type="text" required>',
        '<label>输入银行卡号</label>',
        '</div>',
        '<div class="space-1"></div>',
        '<div class="mui-btn mui-btn--raised mui-btn--orange block" id="mui-unBankBind-submit">解除银行卡</div>',
        '</div>',
        '</div>'
    ].join('');

    //进入游戏 html
    var _goGameModelHtml = [
        '<div id="mui-goGame-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-goGame-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-error-message"></div>',
        '<div id="mui-goGame-message" class="mui-overlay-message"></div>',
        '<div class="mui-btn mui-btn--raised mui-btn--pink right" id="mui-goGame-cancel">取消</div>',
        '<div class="mui-btn mui-btn--raised mui-btn--orange right" id="mui-goGame-submit">进入游戏</div>',
        '</div>',
        '</div>'
    ].join('');


    //支付宝扫描账号绑定
    var _zfbBindModelHtml = [
        '<div id="mui-zfbBind-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text flaticon-closed">支付宝扫描账号绑定</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-error-message"></div>',
        '<div class="mui-message"><font color="red" style="font-size:80%;">注：支付宝“二维码”扫描，必须用您绑定的支付宝账号进行，否则无法实时到账;每位会员只能绑定一个支付宝帐号</font></div>',
        '<div class="mui-textfield mui-textfield--float-label">',
        '<input id="mui-zfbBind-alipayAccount" type="text" required>',
        '<label>支付宝存款账号</label>',
        '</div>',
        '<div class="mui-textfield mui-textfield--float-label">',
        '<input id="mui-zfbBind-password" type="password" required>',
        '<label>游戏账户密码</label>',
        '</div>',
        '<div class="btn" id="mui-zfbBind-submit">绑定</div>',
        '</div>',
        '</div>'
    ].join('');

    //确认html
    var _confirmModelHtml = [
        '<div id="mui-confirm-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-confirm-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-confirm-message" class="mui-overlay-message" ></div>',
        '<div class="mui-buttons">',
        '<div class="btn btn-mg btn-mgb" id="mui-confirm-submit">确定</div>',
        '<div class="btn btn-reset" id="mui-confirm-cancel">取消</div>',
        '</div>',
        '</div>',
        '</div>'
    ].join('');


    //确认html
    var _goGameOrFunGameModelHtml = [
        '<div id="mui-confirm-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-goGameOrFunGame-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-goGameOrFunGame-message" class="mui-overlay-message" ></div>',
        '<div class="mui-buttons">',
        '<div class="mui-btn mui-btn--raised mui-btn--orange" id="mui-goGameOrFunGame-go">进入游戏</div>',
        '<div class="mui-btn mui-btn--raised mui-btn--pink" id="mui-goGameOrFunGame-fun">试玩游戏</div>',
        '</div>',
        '</div>',
        '</div>'
    ].join('');


    //提款确认html
    var _withdrawalConfirmModelHtml = [
        '<div id="mui-confirm-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div id="mui-withdrawalConfirm-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<table>',
        '<tr><td height="30px">账户姓名&nbsp:&nbsp&nbsp&nbsp&nbsp</td><td><div id="mui-withdrawalConfirm-name"></div></td></tr>',
        '<tr><td height="30px">银行名称 :</td><td><div id="mui-withdrawalConfirm-bankName"></div></td></tr>',
        '<tr><td height="30px">银行账号 :</td><td><div id="mui-withdrawalConfirm-bankAccount"></div></td></tr>',
        '<tr><td height="30px">提款金额 :</td><td><div id="mui-withdrawalConfirm-withdrawalMoney"></div></td></tr>',
        '</table>',
        '<div class="mui-buttons">',
        '<div class="btn btn-mg" id="mui-withdrawalConfirm-submit">确定</div>',
        '<div class="btn btn-mg btn-reset" id="mui-withdrawalConfirm-cancel">取消</div>',
        '</div>',
        '<div class="mui-overlay-message" ><font color="red">温馨提示: 如您的注册姓名与您的收款账户姓名不一致，将导致提款失败!</br>请您联系在线客服!</font></div>',
        '</div>',
        '</div>'
    ].join('');

    var _tipsModelHtml = [
        '<div id="mui-confirm-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1">',
        '<div class="mui-overlay-title"><div id="mui-tips-title" class="mui-overlay-title-text"></div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div id="mui-tips-message" class="mui-overlay-message" ></div>',
        // '<div class="mui-buttons">',
        // 	'<div class="mui-btn submit-btn" id="mui-tips-submit">确定</div>',
        // '</div>',
        '</div>',
        '</div>'
    ].join('');

    //历史银行记录 html
    var _historyBankModelHtml = [
        '<div id="mui-historyBank-model" class="mui-overlay-model mui-col-xs32-10 mui-col-xs32-offset-1 mui-col-xs48-8 mui-col-xs48-offset-2 mui-col-xs64-6 mui-col-xs64-offset-3 mui-col-sm-6 mui-col-sm-offset-3 mui-col-md-4 mui-col-md-offset-4">',
        '<div class="mui-overlay-title"><div class="mui-overlay-title-text">历史姓名记录</div><div class="mui-overlay-close flaticon-symbol49"></div></div>',
        '<div class="mui-panel">',
        '<div class="mui-message"><font color="red" style="font-size:80%;">1. 请务必按照系统提示消息进行存款，银行卡转账“附言”必须填写，支付宝转账无需附言完成之后请点击“我已成功存款”，否则您的款项将无法及时到账</font></div>',
        '<div class="mui-message"><font color="red" style="font-size:80%;">2. 如果您的款项10分钟未能到账，请联系24小时在线客服！</font></div>',
        // '<div class="mui-error-message">1. 请务必按照系统提示消息进行存款，银行卡转账“附言”必须填写，支付宝转账无需附言完成之后请点击“我已成功存款”，否则您的款项将无法及时到账 2.如果您的款项10分钟未能到账，请联系24小时在线客服！</div>',
        '<div id="historyBankList"> <table> <thead> <tr> <td>No.</td>  <td>姓名</td><td>操作</td> </tr> </thead> <tbody id="tbody"></tbody></table></div>',
        '<div class="mui-btn mui-btn--raised mui-btn--pink right" id="mui-historyBank-cancel">返回</div>',
        '</div>',
        '</div>'
    ].join('');


    /**
     * 开启弹窗
     * @param {string} actionName
     * @param {array} argsArray
     */
    that.open = function (name, argsArray) {
        if (typeof _actionFn[name] !== 'function') {
            alert(name + ' model 不存在！')
            return;
        }
        //不重复生成
        if (_$currentId == name) {
            return;
        }
        if (argsArray && argsArray instanceof Array) {
            _$currentModel = _actionFn[name].apply(null, argsArray);
        } else {
            _$currentModel = _actionFn[name].apply(null, []);
        }
        _$currentId = name;

        _$currentModel.find('.mui-overlay-close').one('click', that.close);

        muiObj.overlay('on', _$currentModel[0], {
            onclose: function () {
                _$currentModel.find('.mui-overlay-close').unbind('click', that.close);
                _$currentModel = false;
                _$currentId = false;
            }
        });

        _checkHeight(_$currentModel);
    };
    /**
     * 关闭
     */
    that.close = function () {
        muiObj.overlay('off');
    };

    /**
     * 取得 登入视窗 物件
     * @param {string} redirect 登入后，转址Key，使用mobileManage.redirect
     */
    //  function _getLogin(redirect,param){
    // 	if(!_$loginModel){
    // 		initLoginModel();
    // 	}
    // 	_$loginModel.find('input').removeClass('mui--is-dirty');
    // 	_$loginModel.find('input').removeClass('mui--is-not-empty');
    // 	_$loginModel.find('input').addClass('mui--is-empty');
    // 	_$loginModel.redirect = redirect;
    // 	_$loginModel.param = param;
    // 	_$loginModel.$account.val('');
    // 	_$loginModel.$password.val('');
    // 	_$loginModel.$code.val('');
    // 	_$loginModel.$errorMessage.html('');
    // 	_$loginModel.$image.attr('src',manage.getSecurityCodeUrl()+'?'+Math.random());
    //
    // 	return _$loginModel;
    // };

    /**
     * 开启登出视窗
     *
     */
    // function _getLogout(){
    // 	if(!_$logoutModel){
    // 		initLogoutModel();
    // 	}
    // 	return _$logoutModel;
    // };


    /**
     * 开启电话回波
     *
     */
    function _getMakeCall() {
        if (!_$makeCallModel) {
            initMakeCallModel();
        }
        _$makeCallModel.$phone.val('');
        return _$makeCallModel;
    };


    /**
     * 修改密码视窗
     */
    function _getModifyPassword() {
        if (!_$modifyPasswordModel) {
            // initModifyPasswordModel();
        }
        _$modifyPasswordModel.find('input').removeClass('mui--is-dirty');
        _$modifyPasswordModel.find('input').removeClass('mui--is-not-empty');
        _$modifyPasswordModel.find('input').addClass('mui--is-empty');
        _$modifyPasswordModel.$password.val('');
        _$modifyPasswordModel.$newPassword.val('');
        _$modifyPasswordModel.$confirmPassword.val('');
        _$modifyPasswordModel.$errorMessage.html('');

        return _$modifyPasswordModel;
    };

    /**
     * 设定密保问题
     */
    function _getQuestion() {
        if (!_$questionModel) {
            initQuestionModel();
        }
        _$questionModel.find('input').removeClass('mui--is-dirty');
        _$questionModel.find('input').removeClass('mui--is-not-empty');
        _$questionModel.find('input').addClass('mui--is-empty');
        _$questionModel.$question.val('1');
        _$questionModel.$answer.val('');
        _$questionModel.$password.val('');
        _$questionModel.$errorMessage.html('');

        return _$questionModel;
    };


    /**
     * 开启公告视窗
     * @param {object} data 传入公告资料
     * @param {integer} active 显示该则公告
     *
     */
    function _getNews(data, active) {
        if (!_$newsModel) {
            initNewsModel();
        }
        _$newsModel.data = data;
        _$newsModel.active = active;
        _$newsModel.$title.html(_$newsModel.data[_$newsModel.active].title);
        var content = _$newsModel.data[_$newsModel.active].content;
        content = content ? content.replace(/\n/g, "<br/>") : '';
        _$newsModel.$message.html(content);

        return _$newsModel;
    };

    /**
     * 檔案下载
     */
    function _getDownload(data) {
        if (!_$downloadModel) {
            initDownloadModel();
        }

        _$downloadModel.data = data;
        _$downloadModel.$title.html(_$downloadModel.data.title);
        _$downloadModel.$message.html(_$downloadModel.data.content);

        return _$downloadModel;
    };

    /**
     * 进入游戏或下载客户端
     */
    function _getGoOrDownloadGame(data) {
        if (!_$goOrDownloadGameModel) {
            initGoOrDownloadGameModel();
        }
        _$goOrDownloadGameModel.data = data;
        _$goOrDownloadGameModel.$title.html(_$goOrDownloadGameModel.data.title);
        _$goOrDownloadGameModel.$message.html(_$goOrDownloadGameModel.data.content);
        _$goOrDownloadGameModel.$goGame.html(_$goOrDownloadGameModel.data.goGameText || '网页游戏');
        _$goOrDownloadGameModel.$download.html(_$goOrDownloadGameModel.data.downloadText || '下载客户端');
        return _$goOrDownloadGameModel;
    }

    /**
     * 绑定银行卡/支付宝
     */
    function _getBankBind() {
        if (!_$bankBindModel) {
            initBankBindModel();
        }
        _$bankBindModel.find('input').removeClass('mui--is-dirty');
        _$bankBindModel.find('input').removeClass('mui--is-not-empty');
        _$bankBindModel.find('input').addClass('mui--is-empty');
        _$bankBindModel.$password.val('');
        _$bankBindModel.$bankName.val('');
        _$bankBindModel.$cardNo.val('');
        _$bankBindModel.$bindingCode.val('');
        _$bankBindModel.$errorMessage.html('');
        _$bankBindModel.$bindingCode.parent().css('display', 'none');
        _$bankBindModel.$buttons.css('display', 'none');

        return _$bankBindModel;
    };

    function _getZfbBind() {
        if (!_$zfbDepositBindModel) {
            initZfbBindModel();
        }
        _$zfbDepositBindModel.find('input').removeClass('mui--is-dirty');
        _$zfbDepositBindModel.find('input').removeClass('mui--is-not-empty');
        _$zfbDepositBindModel.find('input').addClass('mui--is-empty');
        _$zfbDepositBindModel.$alipayAccount.val('');
        _$zfbDepositBindModel.$password.val('');

        return _$zfbDepositBindModel;
    }

    /**
     * 取得 确认 物件
     * @param {Object} param 参数
     */
    function _getConfirm(config) {
        if (!_$confirmModel) {
            initConfirmModel();
        }
        var _config = {
            title: '输入标题',
            message: '内容',
            callback: false
        };
        $.extend(_config, config);
        _$confirmModel.config = _config;
        _$confirmModel.$title.html(_config.title);
        _$confirmModel.$message.html(_config.message);
        return _$confirmModel;
    };

    /**
     * 取得 进入游戏或测试游戏 物件
     * @param {Object} param 参数
     */
    function _getGoGameOrFunGame(config) {
        if (!_$goGameOrFunGameModel) {
            initGoGameOrFunGameModel();
        }
        var _config = {
            title: '输入标题',
            message: '内容',
            goGame: false,
            goFun: false
        };

        $.extend(_config, config);
        _$goGameOrFunGameModel.config = _config;
        _$goGameOrFunGameModel.$title.html(_config.title);
        _$goGameOrFunGameModel.$message.html(_config.message);
        return _$goGameOrFunGameModel;
    };


    /**
     * 进入游戏
     */
    function _getGoGame(data) {
        if (!_$goGameModel) {
            initGoGameModel();
        }
        _$goGameModel.$error.html('');
        _$goGameModel.data = data;
        _$goGameModel.$title.html(_$goGameModel.data.title);
        _$goGameModel.$message.html(_$goGameModel.data.content);
        _$goGameModel.$error.html(_$goGameModel.data.error);

        return _$goGameModel;
    };

    /**
     * 确认model height 没有超过荧幕高度，超过则不使用置中
     * @param {object} $model 要检查的对象
     */
    function _checkHeight($model) {
        if (($('#mui-overlay').height() - 100) <= $model.height()) {
            $model.addClass('top');
        } else {
            $model.removeClass('top');
        }
    }

    /**
     * 登入 Model 初始化
     */
// 	function initLoginModel(){
// 		_$loginModel = $(_loginModelHtml);
// 		_$loginModel.$account = _$loginModel.find('#mui-login-account');
// 		_$loginModel.$password = _$loginModel.find('#mui-login-password');
// 		_$loginModel.$code = _$loginModel.find('#mui-login-code');
// 		_$loginModel.$image = _$loginModel.find('#mui-login-img');
// 		_$loginModel.$errorMessage = _$loginModel.find('.mui-error-message');
//
// 		_$loginModel.$image.click(function(){
// 			_$loginModel.$image.attr('src',manage.getSecurityCodeUrl()+'?'+Math.random());
// 		});
//
// 		_$loginModel.$submit = _$loginModel.find('#mui-login-submit');
//
// 		_$loginModel.$submit.click(function(){
// 			var formData = {
// 				account:_$loginModel.$account.val(),
// 				password:_$loginModel.$password.val(),
// 				imageCode:_$loginModel.$code.val()
// 			};
// 			manage.getLoader().open('验证中');
// 			manage.getUserManage().login(formData, function(result){
// 				if(result.success){
// //					alert(result.message);
// //					that.close();
// 					manage.redirect(_$loginModel.redirect?_$loginModel.redirect :'index',_$loginModel.param);
// 				}else{
// 					_$loginModel.$code.val('');
// 					_$loginModel.$image.attr('src',manage.getSecurityCodeUrl()+'?'+Math.random());
// 					_$loginModel.$errorMessage.html(result.message);
// 					_checkHeight(_$loginModel);
// //					alert(result.message);
// 				}
// 				manage.getLoader().close();
// 				formData = null;
// 			});
// 		});
//
// 		_$loginModel.bind("keyup",function(e){
//             if(e.which=='13'&&_$loginModel.find('input').is(":focus")){
//             	_$loginModel.$submit.click();
//             }
//         });
//
// 	}

    /**
     * 登出 Model 初始化
     */
    // function initLogoutModel(){
    // 	_$logoutModel = $(_logoutModelHtml);
    //
    // 	_$logoutModel.find('#mui-logout-cancel').click(function(){
    // 		that.close();
    // 	});
    // 	_$logoutModel.find('#mui-logout-submit').click(function(){
    // 		manage.getLoader().open('登出中');
    // 		manage.getUserManage().logout( function(data){
    // 			if(data.success){
    // 				that.close();
    // 				manage.redirect('index');
    // 			}else{
    // 				alert(data.message);
    // 				manage.getLoader().close();
    // 			}
    // 		});
    // 	});
    // }


    /**
     * 修改密码 Model 初始化
     */
    // function initModifyPasswordModel(){
    // 	_$modifyPasswordModel = $(_modifyPasswordModelHtml);
    // 	_$modifyPasswordModel.$password = _$modifyPasswordModel.find('#mui-modifyPassword-password');
    // 	_$modifyPasswordModel.$newPassword = _$modifyPasswordModel.find('#mui-modifyPassword-newPassword');
    // 	_$modifyPasswordModel.$confirmPassword = _$modifyPasswordModel.find('#mui-modifyPassword-confirmPassword');
    // 	_$modifyPasswordModel.$errorMessage = _$modifyPasswordModel.find('.mui-error-message');
    //
    // 	_$modifyPasswordModel.find('#mui-modifyPassword-cancel').click(function(){
    // 		that.close();
    // 	});
    // 	_$modifyPasswordModel.$submit = _$modifyPasswordModel.find('#mui-modifyPassword-submit');
    // 	_$modifyPasswordModel.$submit.click(function(){
    // 		var formData = {
    // 			password:_$modifyPasswordModel.$password.val(),
    // 			newPassword:_$modifyPasswordModel.$newPassword.val(),
    // 			confirmPassword:_$modifyPasswordModel.$confirmPassword.val()
    // 		};
    // 		manage.getLoader().open("修改中");
    // 		manage.getUserManage().changePassword(formData, function(result){
    // 			manage.getLoader().close();
    // 			if(result.success){
    // 				alert(result.message);
    // 				that.close();
    // 				window.location.href='/mobile';
    // 			}else{
    // 				_$modifyPasswordModel.$errorMessage.html(result.message);
    // 				_checkHeight(_$modifyPasswordModel);
    // 				alert(result.message);
    // 			}
    // 		});
    // 	});
    //
    // 	_$modifyPasswordModel.bind("keyup",function(e){
    //        if(e.which=='13'&&_$modifyPasswordModel.find('input').is(":focus")){
    //        	_$modifyPasswordModel.$submit.click();
    //        }
    //    });
    // }

    /**
     * 电话回播 Model 初始化
     */
    function initMakeCallModel() {
        _$makeCallModel = $(_makeCallModelHtml);
        _$makeCallModel.$phone = _$makeCallModel.find('#mui-makeCall-phone');
        _$makeCallModel.$code = _$makeCallModel.find('#getImgTryCode');
        _$makeCallModel.find('#mui-makeCall-cancel').click(function () {
            that.close();
        });
        _$makeCallModel.$submit = _$makeCallModel.find('#mui-makeCall-submit');
        _$makeCallModel.$submit.click(function () {
            var formData = {
                phone: _$makeCallModel.$phone.val(),
                imgCode: _$makeCallModel.$code.val(),
            };
            manage.getLoader().open('处理中');
            manage.getUserManage().makeCall(formData, function (result) {
                if (result.success) {
                    alert(result.message);
                } else {
                    alert(result.message);
                }
                manage.getLoader().close();
                formData = null;
            });
        });

        _$makeCallModel.bind("keyup", function (e) {
            if (e.which == '13' && _$makeCallModel.find('input').is(":focus")) {
                _$makeCallModel.$submit.click();
            }
        });
    }

    /**
     * 密保问题 Model 初始化
     */
    function initQuestionModel() {
        var questionData = [
            {value: '1', name: '您最喜欢的明星名字？'},
            {value: '2', name: '您最喜欢的职业？'},
            {value: '3', name: '您最喜欢的城市名称？'},
            {value: '4', name: '对您影响最大的人名字是？'},
            {value: '5', name: '您就读的小学名称？'},
            {value: '6', name: '您最熟悉的童年好友名字是？'}
        ];
        var optionHtml = '<option value="{0}">{1}</option>';
        var options = new Array();

        _$questionModel = $(_questionModelHtml);
        _$questionModel.$question = _$questionModel.find('#mui-question-question');
        _$questionModel.$answer = _$questionModel.find('#mui-question-answer');
        _$questionModel.$password = _$questionModel.find('#mui-question-password');
        _$questionModel.$errorMessage = _$questionModel.find('.mui-error-message');

        for (var i = 0; i < questionData.length; i++) {
            options.push(String.format(optionHtml, questionData[i]['value'], questionData[i]['name']));
        }
        _$questionModel.$question.append(options);

        _$questionModel.find('#mui-question-cancel').click(function () {
            that.close();
        });
        _$questionModel.$submit = _$questionModel.find('#mui-question-submit');
        _$questionModel.find('#mui-question-submit').click(function () {
            var formData = {
                password: _$questionModel.$password.val(),
                answer: _$questionModel.$answer.val(),
                questionId: _$questionModel.$question.val()
            };
            manage.getLoader().open("设置中");
            manage.getUserManage().saveQuestion(formData, function (result) {
                manage.getLoader().close();
                if (result.success) {
                    alert(result.message);
                    that.close();
                } else {
                    alert(result.message);
                    _$questionModel.$errorMessage.html(result.message);
                    _checkHeight(_$questionModel);
                }
            });
        });

        _$questionModel.bind("keyup", function (e) {
            if (e.which == '13' && _$questionModel.find('input').is(":focus")) {
                _$questionModel.$submit.click();
            }
        });
        optionHtml = options = questionData = null;
    }

    /**
     * 公告信息 Model 初始化
     */
    function initNewsModel() {
        _$newsModel = $(_newsModelHtml);
        _$newsModel.$title = _$newsModel.find('#mui-news-title');
        _$newsModel.$message = _$newsModel.find('#mui-news-message');

        _$newsModel.find('#mui-news-last').click(function () {
            if (_$newsModel.active >= 0) {
                _$newsModel.active--;
            }
            if (!_$newsModel.data[_$newsModel.active]) {
                _$newsModel.$title.html('公告');
                _$newsModel.$message.html('无上一条公告');
            } else {
                _$newsModel.$title.html(_$newsModel.data[_$newsModel.active].title);
                var content = _$newsModel.data[_$newsModel.active].content;
                content = content ? content.replace(/\n/g, "<br/>") : '';
                _$newsModel.$message.html(content);
            }
            _checkHeight(_$newsModel);
        });

        _$newsModel.find('#mui-news-next').click(function () {
            if (_$newsModel.active < _$newsModel.data.length) {
                _$newsModel.active++;
            }
            if (!_$newsModel.data[_$newsModel.active]) {
                _$newsModel.$title.html('公告');
                _$newsModel.$message.html('无下一条公告');
            } else {
                _$newsModel.$title.html(_$newsModel.data[_$newsModel.active].title);
                var content = _$newsModel.data[_$newsModel.active].content;
                content = content ? content.replace(/\n/g, "<br/>") : '';
                _$newsModel.$message.html(content);
            }
            _checkHeight(_$newsModel);
        });
    }

    /**
     * 檔案下载 Model 初始化
     */
    function initDownloadModel() {

        _$downloadModel = $(_downloadModelHtml);
        _$downloadModel.$title = _$downloadModel.find('#mui-download-title');
        _$downloadModel.$message = _$downloadModel.find('#mui-download-message');

        _$downloadModel.find('#mui-download-cancel').click(function () {
            that.close();
        });
        _$downloadModel.find('#mui-download-submit').click(function (e) {
            if ($.isFunction(_$downloadModel.data.handler)) {
                _$downloadModel.data.handler(null, [e, _$downloadModel]);
            } else {
                window.location.href = _$downloadModel.data.url;
                that.close();
            }
        });
    }

    /**
     * 初始化 进入游戏或下载APP Model
     */
    function initGoOrDownloadGameModel() {
        _$goOrDownloadGameModel = $(_goGameOrdownloadModelHtml);
        _$goOrDownloadGameModel.$title = _$goOrDownloadGameModel.find('#mui-goOrDownload-title');
        _$goOrDownloadGameModel.$message = _$goOrDownloadGameModel.find('#mui-goOrDownload-message');
        _$goOrDownloadGameModel.$goGame = _$goOrDownloadGameModel.find('#mui-goGame-submit');
        _$goOrDownloadGameModel.$download = _$goOrDownloadGameModel.find('#mui-download-submit');

        _$goOrDownloadGameModel.$download.click(function (e) {
            if ($.isFunction(_$goOrDownloadGameModel.data.goDownloadFn)) {
                _$goOrDownloadGameModel.data.goDownloadFn.apply(null, [e, _$goOrDownloadGameModel]);
            } else {
                window.location.href = _$goOrDownloadGameModel.data.download_url;
                that.close();
            }
        });
        _$goOrDownloadGameModel.$goGame.click(function (e) {
            if ($.isFunction(_$goOrDownloadGameModel.data.goGameFn)) {
                _$goOrDownloadGameModel.data.goGameFn.apply(null, [e, _$goOrDownloadGameModel]);
            } else {
                window.location.href = _$goOrDownloadGameModel.data.game_url;
                that.close();
            }
        });
    }

    /**
     * 绑定支付宝 Model 初始化
     */
    function initBankBindModel() {
        var bankBindData = [
            {value: '', name: '请选择'},
            {value: '交通银行', name: '交通银行'},
            {value: '工商银行', name: '工商银行'},
            {value: '农业银行', name: '农业银行'},
            {value: '中国银行', name: '中国银行'},
            {value: '建设银行', name: '建设银行'},
            {value: '招商银行', name: '招商银行'},
            {value: '中信银行', name: '中信银行'},
            {value: '华夏银行', name: '华夏银行'},
            {value: '光大银行', name: '光大银行'},
            {value: '民生银行', name: '民生银行'},
            {value: '浦发银行', name: '浦发银行'},
            {value: '广发银行', name: '广发银行'},
            {value: '兴业银行', name: '兴业银行'},
            {value: '平安银行', name: '平安银行'},
            {value: '徽商银行', name: '徽商银行'},
            {value: '浙商银行', name: '浙商银行'},
            {value: '渤海银行', name: '渤海银行'},
            {value: '恒丰银行', name: '恒丰银行'},
            {value: '邮政银行', name: '邮政银行'}
        ];
        var optionHtml = '<option value="{0}">{1}</option>';
        var options = new Array();
        _$bankBindModel = $(_bankBindModelHtml);
        _$bankBindModel.$bankName = _$bankBindModel.find('#mui-bankBind-bankName');
        _$bankBindModel.$cardNo = _$bankBindModel.find('#mui-bankBind-cardNo');
        _$bankBindModel.$password = _$bankBindModel.find('#mui-bankBind-password');
        _$bankBindModel.$bindingCode = _$bankBindModel.find('#mui-bankBind-bindingCode');
        _$bankBindModel.$buttons = _$bankBindModel.find('#mui-bankBind-buttons');
        _$bankBindModel.$errorMessage = _$bankBindModel.find('.mui-error-message');

        // //仅能输入数字
        // _$bankBindModel.$cardNo.keyup(function () {
        //     var c = $(this);
        //     if (/[^\d]/.test(c.val())) {//替换非数字字符
        //         var temp_amount = c.val().replace(/[^\d]/g, '');
        //         $(this).val(temp_amount);
        //     }
        // })

        for (var i = 0; i < bankBindData.length; i++) {
            options.push(String.format(optionHtml, bankBindData[i]['value'], bankBindData[i]['name']));
        }

        _$bankBindModel.$bankName.append(options);

        _$bankBindModel.$bindingCode.parent().css('display', 'none');
        _$bankBindModel.$buttons.css('display', 'none');

        _$bankBindModel.$bankName.bind('change', function () {
            if (this.value == '支付宝') {
                _$bankBindModel.$bindingCode.parent().css('display', 'block');
                _$bankBindModel.$buttons.css('display', 'block');
            } else {
                _$bankBindModel.$bindingCode.parent().css('display', 'none');
                _$bankBindModel.$buttons.css('display', 'none');
            }
            _checkHeight(_$bankBindModel);
        });

        _$bankBindModel.find('#mui-bankBind-cancel').click(function () {
            that.close();
        });
        _$bankBindModel.find('#mui-bankBind-voice').click(function () {
            //点触验证
            mobileManage.openTouClick(function (tResult) {
                if (tResult.success) {
                    manage.getLoader().open("发送中");
                    manage.ajax({
                        url: 'mobi/sendAlipayPhoneVoiceCode.php',
                        param: {
                            bankName: _$bankBindModel.$bankName.val(),
                            checkAddress: tResult.data.checkAddress.toString(),
                            checkKey: tResult.data.token
                        },
                        callback: function (result) {
                            _$bankBindModel.$errorMessage.html(result.message);
                            alert(result.message)
                            _checkHeight(_$bankBindModel);
                            manage.getLoader().close();
                        }
                    });
                } else {
                    alert(tResult.message);
                }
            });
        });

        _$bankBindModel.find('#mui-bankBind-sms').click(function () {
            //点触验证
            mobileManage.openTouClick(function (tResult) {
                if (tResult.success) {
                    manage.getLoader().open("发送中");
                    manage.ajax({
                        url: 'mobi/sendAlipayPhoneSmsCode.php',
                        param: {
                            bankName: _$bankBindModel.$bankName.val(),
                            checkAddress: tResult.data.checkAddress.toString(),
                            checkKey: tResult.data.token
                        },
                        callback: function (result) {
                            _$bankBindModel.$errorMessage.html(result.message);
                            alert(result.message)
                            _checkHeight(_$bankBindModel);
                            manage.getLoader().close();
                        }
                    });
                } else {
                    alert(tResult.message);
                }
            });
        });

        _$bankBindModel.$submit = _$bankBindModel.find('#mui-bankBind-submit');

        _$bankBindModel.$submit.click(function () {

            //仅能输入数字

            var cardNo = _$bankBindModel.find('#mui-bankBind-cardNo').val();

            var cardNoReg = /[^\d]/;

            // if (cardNoReg.test(cardNo)) {
            //     alert("[提示]银行卡号只允许为0-9的数字且位数为10-30位，不得包含任何符号与空格！");
            //     return false;
            // }

            var bdXinMing = _$bankBindModel.find('#mui-bankBind-bdXinMing').val();

            bdXinMing = bdXinMing.replace(/[s]+/g, "");

            // var myReg = /^[\u4e00-\u9fa5]+$/;
            // var myReg = /^[\u4E00-\u9FA5\uf900-\ufa2d·s]{2,20}$/;
            // if (!myReg.test(bdXinMing)) {
            //     alert("[提示]姓名只允许为汉字，不得包含任何符号与空格！");
            //     return false;
            // }

            var formData = {
                cardNo: _$bankBindModel.$cardNo.val(),
                bankName: _$bankBindModel.$bankName.val(),
                password: _$bankBindModel.$password.val(),
                addr: 'none',
                bindingCode: _$bankBindModel.$bindingCode.val(),
                accountName: bdXinMing
            };
            manage.getLoader().open("绑定中");
            manage.getBankManage().bindBankNo(formData, function (result) {
                manage.getLoader().close();
                if (result.success) {
                    alert(result.message);
                    that.close();
                } else {
                    alert(result.message);
                    _$bankBindModel.$errorMessage.html(result.message);
                    _checkHeight(_$bankBindModel);
                }
            });
        });

        _$bankBindModel.find('#mui-bankBind-cancel').click(function () {
            that.close();
        });

        _$bankBindModel.$blur = _$bankBindModel.find('#mui-bankBind-cardNo');

        _$bankBindModel.$blur.on('change', function () {

            var identifycode = $('#mui-bankBind-cardNo').val();

            console.log(identifycode);
            if (identifycode.length < 10) {
                alert('请您输入正确的银行卡号');
                return;
            } else {
                $.post("/asp/getBankInfo.php", {"bankno": identifycode},
                    function (returnedData) {
                        if (returnedData == "我们不支持此银行卡") {
                            alert(returnedData);
                        } else if (returnedData == "您输入的银行卡信息错误") {
                            alert('您所输入的银行卡位数不正确!');
                        } else {
                            // $('#mui-bankBind-bankName option').each(function () {
                            //     var bankName = $(this).val();
                            //     if (bankName == returnedData.issuebankname) {
                            //         $(this).attr('selected', true);
                            //         return;
                            //     } else {
                            //         $(this).attr('selected', false);
                            // });

                            $('#mui-bankBind-bankName').val(returnedData.issuebankname)
                        }
                    });
            }

        });

        _$bankBindModel.bind("keyup", function (e) {
            if (e.which == '13' && _$bankBindModel.find('input').is(":focus")) {
                _$bankBindModel.$submit.click();
            }
        });
        var bk = _$bankBindModel.find('#mui-bankBind-bdXinMing');
        bk.val($('#nm').val());
        if (bk.val() != "") {
            bk.attr('readonly', 'readonly');
        } else {
            // bk.attr('placeholder','请填写与您银行卡一致的真实姓名');
            bk.removeAttr("readonly");
        }
        optionHtml = options = bankBindData = null;
    }

    /**
     * 进入游戏 Model 初始化
     */
    function initGoGameModel() {

        _$goGameModel = $(_goGameModelHtml);
        _$goGameModel.$title = _$goGameModel.find('#mui-goGame-title');
        _$goGameModel.$message = _$goGameModel.find('#mui-goGame-message');
        _$goGameModel.$error = _$goGameModel.find('.mui-error-message');

        _$goGameModel.find('#mui-goGame-cancel').click(function () {
            that.close();
        });

        _$goGameModel.find('#mui-goGame-submit').click(function (e) {

            if (_$goGameModel.data.onSubmit) {
                _$goGameModel.data.onSubmit.apply(_$goGameModel, [e, _$goGameModel]);
            } else {
                if (!_$goGameModel.data || !_$goGameModel.data.url) {
                    _$goGameModel.$error.html('游戏路径不存在！');
                    alert('游戏路径不存在！')
                    return;
                }
                window.location.href = _$goGameModel.data.url;
                that.close();
            }
        });
    }


    function initZfbBindModel() {
        _$zfbDepositBindModel = $(_zfbBindModelHtml);
        _$zfbDepositBindModel.$errorMessage = _$zfbDepositBindModel.find('.mui-error-message');
        _$zfbDepositBindModel.$alipayAccount = _$zfbDepositBindModel.find("#mui-zfbBind-alipayAccount");
        _$zfbDepositBindModel.$password = _$zfbDepositBindModel.find("#mui-zfbBind-password");
        _$zfbDepositBindModel.$submit = _$zfbDepositBindModel.find("#mui-zfbBind-submit");

        _$zfbDepositBindModel.find('#mui-zfbBind-cancel').click(function () {
            that.close();
        });

        _$zfbDepositBindModel.$submit.click(function () {
            manage.getLoader().open("绑定中");
            manage.getBankManage().bindZFBQR({
                account: _$zfbDepositBindModel.$alipayAccount.val(),
                password: _$zfbDepositBindModel.$password.val()
            }, function (result) {
                manage.getLoader().close();
                _$zfbDepositBindModel.$errorMessage.html(result.message);
                _checkHeight(_$zfbDepositBindModel);
                alert(result.message);
            });
        });

        _$zfbDepositBindModel.bind("keyup", function (e) {
            if (e.which == '13' && _$zfbDepositBindModel.find('input').is(":focus")) {
                _$zfbDepositBindModel.$submit.click();
            }
        });
    }

    /**
     * 确认 Model 初始化
     */
    function initConfirmModel() {
        _$confirmModel = $(_confirmModelHtml);
        _$confirmModel.$title = _$confirmModel.find('#mui-confirm-title');
        _$confirmModel.$message = _$confirmModel.find('#mui-confirm-message');

        _$confirmModel.find('#mui-confirm-cancel').click(function () {
            that.close();
            if (typeof _$confirmModel.config.callback === 'function') {
                _$confirmModel.config.callback(false);
            }
        });

        _$confirmModel.find('#mui-confirm-submit').click(function () {
            that.close();
            if (typeof _$confirmModel.config.callback === 'function') {
                _$confirmModel.config.callback(true);
            }
        });
    }


    /**
     * 确认 Model 初始化
     */
    function initGoGameOrFunGameModel() {
        _$goGameOrFunGameModel = $(_goGameOrFunGameModelHtml);
        _$goGameOrFunGameModel.$title = _$goGameOrFunGameModel.find('#mui-goGameOrFunGame-title');
        _$goGameOrFunGameModel.$message = _$goGameOrFunGameModel.find('#mui-goGameOrFunGame-message');

        _$goGameOrFunGameModel.find('#mui-goGameOrFunGame-go').click(function () {
            that.close();
            if (typeof _$goGameOrFunGameModel.config.goGame === 'function') {
                _$goGameOrFunGameModel.config.goGame();
            }
        });

        _$goGameOrFunGameModel.find('#mui-goGameOrFunGame-fun').click(function () {
            that.close();
            if (typeof _$goGameOrFunGameModel.config.goFun === 'function') {
                _$goGameOrFunGameModel.config.goFun();
            }
        });
    }

    /**
     * 提款确认 物件
     * @param {Object} param 参数
     */
    function _withdrawalConfirm(config) {
        if (!_$withdrawalConfirmModel) {
            initWithdrawalConfirmModel();
        }
        var _config = {
            title: '输入标题',
            name: 'xxx',
            bankName: '内容',
            bankAccount: '内容',
            withdrawalMoney: 'xxx',
            withdrawlType:'',
            callback: false
        };

        $.extend(_config, config);
        _$withdrawalConfirmModel.config = _config;
        _$withdrawalConfirmModel.$title.html(_config.title);
        _$withdrawalConfirmModel.$name.html(_config.name);
        _$withdrawalConfirmModel.$bankName.html(_config.bankName);
        _$withdrawalConfirmModel.$bankAccount.html(_config.bankAccount);
        _$withdrawalConfirmModel.$withdrawalMoney.html(_config.withdrawalMoney);
        _$withdrawalConfirmModel.$withdrawalType.html(_config.withdrawalType);
        return _$withdrawalConfirmModel;
    };

    /**
     * 提款确认 Model 初始化
     */
    function initWithdrawalConfirmModel() {
        _$withdrawalConfirmModel = $(_withdrawalConfirmModelHtml);
        _$withdrawalConfirmModel.$title = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-title');
        _$withdrawalConfirmModel.$name = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-name');
        _$withdrawalConfirmModel.$bankName = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-bankName');
        _$withdrawalConfirmModel.$bankAccount = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-bankAccount');
        _$withdrawalConfirmModel.$withdrawalMoney = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-withdrawalMoney');
        _$withdrawalConfirmModel.$withdrawalType = _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-withdrawalType');
        _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-cancel').click(function () {
            that.close();
            if (typeof _$withdrawalConfirmModel.config.callback === 'function') {
                _$withdrawalConfirmModel.config.callback(false);
            }
        });

        _$withdrawalConfirmModel.find('#mui-withdrawalConfirm-submit').click(function () {
            that.close();
            if (typeof _$withdrawalConfirmModel.config.callback === 'function') {
                _$withdrawalConfirmModel.config.callback(true);
            }
        });


    }


    /**
     * 提示(tips)
     * @param {Object} param 参数
     */
    function _getTips(config) {
        if (!_$tipsModel) {
            initTipsModel();
        }
        var _config = {
            title: '输入标题',
            message: '内容',
            callback: false
        };
        $.extend(_config, config);
        _$tipsModel.config = _config;
        _$tipsModel.$title.html(_config.title);
        _$tipsModel.$message.html(_config.message);
        return _$tipsModel;
    };

    /**
     * 提示(tips) Model 初始化
     */
    function initTipsModel() {
        _$tipsModel = $(_tipsModelHtml);
        _$tipsModel.$title = _$tipsModel.find('#mui-tips-title');
        _$tipsModel.$message = _$tipsModel.find('#mui-tips-message');

        _$tipsModel.find('#mui-tips-submit').click(function () {
            that.close();
            if (typeof _$tipsModel.config.callback === 'function') {
                _$tipsModel.config.callback(true);
            }
        });
    }


    /**
     * 历史银行记录(historyBank)
     * @param {Object} param 参数
     */
    function _getHistoryBank(config) {
        if (!_$historyBankModal) {
            initHistoryBankModel();
        }
        var _config = {
            title: '输入标题',
            message: '内容',
            callback: false
        };
        $.extend(_config, config);
        _$historyBankModal.config = _config;

        return _$historyBankModal;
    };

    /**
     * 历史银行记录(historyBank) Model 初始化
     */
    function initHistoryBankModel() {
        _$historyBankModal = $(_historyBankModelHtml);
        var _testMode = 0;

        if (_testMode == 1) {
            var jsonData = '{"pageNumber":1,"totalPages":12,"size":10,"pageContents":[{"depositId":"esqrbd","loginname":"james","accountname":"汤书兵","bankname":"招商银行","bankno":"6214837906643162","status":0,"createtime":"Feb 26, 2017 12:00:00 AM","ubankname":"中国交通银行","uaccountname":"123","ubankno":"12345678901234567","amount":123.0,"flag":1,"type":"1"},{"depositId":"sxg7xazi","loginname":"james","accountname":"徐安江","bankname":"招商银行","bankno":"6214832708697649","status":2,"createtime":"Feb 24, 2017 12:00:00 AM","updatetime":"Feb 24, 2017 12:00:00 AM","ubankname":"中国工商银行","uaccountname":"尼玛","ubankno":"1556451546481434","amount":10.0,"flag":1,"type":"0"},{"depositId":"68j78a97","loginname":"james","accountname":"苏德喜","bankname":"招商银行","bankno":"6214832017301412","status":2,"createtime":"Feb 24, 2017 12:00:00 AM","updatetime":"Feb 24, 2017 12:00:00 AM","ubankname":"支付宝","uaccountname":"1","ubankno":"","amount":2.0,"flag":1,"type":"2"},{"depositId":"mywkv","loginname":"James","accountname":"冷祥富","bankname":"支付宝","bankno":"lengfengzhong6@sina.com","status":2,"createtime":"Aug 9, 2016 12:00:00 AM","updatetime":"Feb 24, 2017 12:00:00 AM","flag":1}],"statics1":0.0,"statics2":0.0,"totalRecords":112,"numberOfRecordsShown":4,"jsPageCode":"共112条,每页10条,当前1/12\u0026nbsp;首頁\u0026nbsp;上一页\u0026nbsp;\u003ca href\u003d\u0027javascript:gopage(2)\u0027\u003e下一页\u003c/a\u003e\u0026nbsp;\u003ca href\u003d\u0027javascript:gopage(12)\u0027\u003e末页\u003c/a\u003e\u0026nbsp;到第\u003cselect name\u003d\u0027page\u0027 onchange\u003d\u0027javascript:gopage(this.options[this.selectedIndex].value)\u0027\u003e\u003coption value\u003d\u00271\u0027 selected\u003e1\u003c/option\u003e\u003coption value\u003d\u00272\u0027\u003e2\u003c/option\u003e\u003coption value\u003d\u00273\u0027\u003e3\u003c/option\u003e\u003coption value\u003d\u00274\u0027\u003e4\u003c/option\u003e\u003coption value\u003d\u00275\u0027\u003e5\u003c/option\u003e\u003coption value\u003d\u00276\u0027\u003e6\u003c/option\u003e\u003coption value\u003d\u00277\u0027\u003e7\u003c/option\u003e\u003coption value\u003d\u00278\u0027\u003e8\u003c/option\u003e\u003coption value\u003d\u00279\u0027\u003e9\u003c/option\u003e\u003coption value\u003d\u002710\u0027\u003e10\u003c/option\u003e\u003coption value\u003d\u002711\u0027\u003e11\u003c/option\u003e\u003coption value\u003d\u002712\u0027\u003e12\u003c/option\u003e\u003c/select\u003e页"}';
            jsonData = JSON.parse(jsonData);
        } else {
            var jsonData = ajaxPost("/asp/queryDepositBank.php", "");
        }

        if (jsonData && typeof jsonData != 'undefined') {
            var pageContents = jsonData.pageContents;

            var html = "";
            if (pageContents.length > 0) {
                var pageLength = (pageContents.length > 5) ? 5 : pageContents.length;
                for (var i = 0; i < pageLength; i++) {

                    var type = (pageContents[i].type) ? pageContents[i].type : "";
                    // var bankname = (pageContents[i].bankname) ? pageContents[i].bankname : "";
                    var ubankname = (pageContents[i].ubankname) ? pageContents[i].ubankname : "";
                    var uaccountname = (pageContents[i].uaccountname) ? pageContents[i].uaccountname : "";
                    var ubankno = (pageContents[i].ubankno) ? pageContents[i].ubankno : "";

                    var loginname = (pageContents[i].loginname) ? pageContents[i].loginname : "";
                    var depositid = (pageContents[i].depositId) ? pageContents[i].depositId : "";

                    html += "<tr>";

                    html += "<td>" + (i + 1) + "</td>";
                    // html += "<td>" + ubankname + "</td>";
                    html += "<td>" + uaccountname + "</td>";
                    // html += "<td>" + ubankno + "</td>";
                    html += "<td>";
                    html += "<input type=\"button\" value=\"选中\" class=\"mui-btn mui-btn--primary quick-save-choose-btn small\" data-type='" + type + "' data-name='" + uaccountname + "' data-bank='" + ubankname + "' data-card='" + ubankno + "'>";
                    html += "<input type=\"button\" value=\"删除\" class=\"mui-btn mui-btn--danger quick-save-delete-btn small\" data-depositid='" + depositid + "' data-loginname='" + loginname + "' data-card='" + ubankno + "'>";
                    html += "</td>";

                    html += "</tr>";
                }
            }

            if (html == "") {
                html = '<tr><td colspan="5">暂无历史记录</td></tr>';
            }

            setTimeout(function () {
                $("#tbody").empty().append(html);
                setChooseBtn();
                setDeleteBtn();
            }, 200);

            _$historyBankModal.find('#mui-historyBank-cancel').click(function () {
                that.close();
            });

            function setChooseBtn() {
                _$historyBankModal.find(".quick-save-choose-btn").click(function () {

                    $("#m-deposit-fast-bank option").each(function () {
                        $(this).removeAttr("selected");
                    });

                    var type = $(this).data("type");
                    var name = $(this).data("name");
                    var bank = $(this).data("bank");
                    var card = $(this).data("card");
                    $("#deposit-fast-card").val(card);

                    if (type == "2") {
                        $("#deposit-fast-card").hide();
                        $("#deposit-fast-card").val("");
                        $("#card").hide();
                        $("#selectpay").hide();
                    } else {
                        $("#deposit-fast-card").show();
                        $("#selectpay").show();
                        $("#card").show();
                    }

                    $("#m-deposit-fast-type").val(type);
                    $("#deposit-fast-name").val(name);

                    // 下拉式選單
                    $("#m-deposit-fast-bank").val(bank);
                    $("#m-deposit-fast-bank option").each(function () {
                        var text = $(this).text();
                        if (text == bank) {
                            $(this).attr("selected", "selected");
                        }
                    });

                    that.close();
                });
            }

            function setDeleteBtn() {
                _$historyBankModal.find(".quick-save-delete-btn").click(function () {
                    var $that = $(this);
                    var $parent = $that.parents("tr");

                    var card = $(this).data("card");
                    var loginname = $(this).data("loginname");
                    var depositid = $(this).data("depositid");

                    if (_testMode == 1) {
                        $parent.remove();
                    } else {
                        var jsonData = ajaxPost("/asp/updateDepositBank.php", {
                            "loginname": loginname,
                            "ubankno": card,
                            "depositId": depositid
                        });
                        if (jsonData != "" && typeof jsonData != "undefined") {
                            $parent.remove();
                        }
                    }

                });
            }

        }
    }

    /**
     * 绑定支付宝 Model 初始化
     */
    function initUnBankBindModel() {
        _$unBankBindModel = $(_unBankBindModelHtml);

        _$unBankBindModel.$cardNo = _$unBankBindModel.find('#mui-bankBind-cardNo');

        //仅能输入数字
        _$unBankBindModel.$cardNo.keyup(function () {
            var c = $(this);
            if (/[^\d]/.test(c.val())) {//替换非数字字符
                var temp_amount = c.val().replace(/[^\d]/g, '');
                $(this).val(temp_amount);
            }
        })
    }

    function _getUnBankBind() {
        if (!_$unBankBindModel) {
            initUnBankBindModel();
        }

        _$unBankBindModel.$cardNo.val("");

        return _$unBankBindModel;
    };


}