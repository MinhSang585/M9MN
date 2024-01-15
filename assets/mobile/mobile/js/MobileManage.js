/**
 * 管理Manage、轉址以及Storage存取
 * 
 */

function MobileManage(baseUrl,securityCodeUrl){
	var that = this;
	var _baseUrl = baseUrl;
	var _securityCodeUrl = securityCodeUrl;

	//管理同样的url 不重复执行   
	var _ajaxObj = {};
	//存放 url param
	var _urlParamValue = {};
	
	//点触Url
	var _touClickUrl = '//js.touclick.com/js.touclick?b=68aca137-f3c5-457b-87a4-8a46880b1e66';
	window.TouClick = false;
	
	var _module = false;
	
	var _loader = false;
	var _userManage = false;
	var _bankManage = false;
	var _TPPManage = false;
	var _selfGetManage = false;
	var _signManage = false;
	var _experienceManage = false;
	
	
	//方法對應
	var _actions = {
		'common':'common',
		'index':'index',
		'login':'login',

		'pcindex':'pcindex',


		'fundsManage':'fundsManage',
        'withdrawal':'withdrawal',
        'transfer':'transfer',
        'balence':'balence',
		'register':'register',
		'modifyPassword':'modifyPassword',
		'online800':'online800',
		'messageGetPassword':'messageGetPassword',
		'messageGetPassword2':'messageGetPassword2',
		'emailGetPassword2':'emailGetPassword2',
		'emailGetPassword':'emailGetPassword',
		'messageGetAccount':'messageGetAccount',
		'emailGetAccount':'emailGetAccount',
		'forgotPassword':'forgotPassword',
		'forgotPassword2':'forgotPassword2',
		'unlock':'unlock',
		'forgotAccount':'forgotAccount',
		'getAccount':'getAccount',
		'license':'license',
		'preferential':'preferential',
		'selfGet':'selfGet',
		'girls':'girls',
		'integral':'integral',
		'zx':'zx',
		'cooperation':'cooperation',
		'question':'question',
		'news':'news',
		'bbs':'bbs',

		'accountHistory':'accountHistory',
		'email':'email',
        'EbetAppWebGame':'EbetAppWebGame',
		'vip':'vip',
		'registerSuccess':'registerSuccess',
		'slotGame':'slotGame',
		'userCenter':'userCenter',
		'history':'history',

		'accountCenter':'accountCenter',

        'info':'info',
        'password':'password',
        'bankHistory':'bankHistory',
		'messageNotice':'messageNotice',
		'securityProtection':'securityProtection',
		'payPassword':'payPassword',
		'bindBankCard':'bindBankCard',

        'agent':'agent',
        'agentHistory':'agentHistory',
        'agentWithdrawal':'agentWithdrawal',
        'agentCenter':'agentCenter',
        'agentRegister':'agentRegister',
        'agentContact':'agentContact',
        'agentResult':'agentResult',
        'agentInfo':'agentInfo',
		'agentCreditLog':'agentCreditLog',
        'agentPtCommission':'agentPtCommission',
        'agentCommission':'agentCommission',
        'agentSubuser':'agentSubuser',
        'agentPlatform':'agentPlatform',
        'agentBetprofit':'agentBetprofit',
        'agentLink':'agentLink',
        'agentSubuser2':'agentSubuser2',
		'agentCommissionGuide2':'agentCommissionGuide2',
		'agentInfo2':'agentInfo2',
		'agentInfoSet2':'agentInfoSet2',
		'agentPtCommission2':'agentPtCommission2',
        'agentCommission2':'agentCommission2',
		'deposit2':'deposit2',
		'withdraw2':'withdraw2',
		'backwater2':'backwater2',
		'promotion2':'promotion2',
		'winOrLose2':'winOrLose2',
		'agentCreditLog2':'agentCreditLog2',
		'password2':'password2',
		'modifyPassword2':'modifyPassword2',
		'addCard2':'addCard2',
		'cardList2':'cardList2',
		'suggestion2':'suggestion2',
		'aliasName2':'aliasName2',
		'actName2':'actName2',
		'birthday2':'birthday2',
		'email2':'email2',
		'microchannel2':'microchannel2',
		'phone2':'phone2',
		'qq2':'qq2',
		'agentWithdrawal2':'agentWithdrawal2',
		'signBonus':'signBonus'

	};



	//所有動作對應的網址
	var _urls = {
		'pcindex':'index.jsp',
		'index':'mobile/index.jsp',
		'login':'mobile/login.jsp',
        'register':'mobile/register.jsp',
        'forgotPassword':'mobile/forgotPassword.jsp',
		'forgotPassword2':'mobile/forgotPassword2.jsp',
		'unlock':'mobile/unlock.jsp',
		'forgotAccount':'mobile/forgotAccount.jsp',
		'getAccount':'mobile/getAccount.jsp',
		'license':'mobile/license.jsp',
        'email':'mobile/email.jsp',
        'vip':'mobile/vip.jsp',
		'girls':'qy-ydpm/girls2/index.jsp',

        'withdrawal':'mobile/funds/withdrawal.jsp',
        'transfer':'mobile/funds/transfer.jsp',
		'fundsManage':'mobile/app/fundsManage.jsp?openMobile',
        'balence':'mobile/balence.jsp',
		'registerSuccess':'mobile/registerSuccess.jsp',
		'slotGame':'mobile/app/gameLobby.jsp',
        'userCenter':'mobile/userCenter.jsp',

		'modifyPassword':'mobile/modifyPassword.jsp',
		'preferential':'mobile/preferential.jsp',
		'selfGet':'mobile/selfGet.jsp',

		'integral':'mobile/integral/integral.jsp',
		'zx':'https://www.qy478.com/',
		'cooperation':'mobile/cooperation.jsp',
		'question':'mobile/saveQuestion.jsp',
		'news':'mobile/news.jsp',

		'bbs':'asp/bbsIndex.php',
		'accountHistory':'mobile/accountHistory.jsp',
		'online800':'https://chatai.l8serviceqy8.com/chat/chatClient/chatbox.jsp?companyID=9037&configID=14',
		'emailGetPassword':'mobile/emailGetPassword.jsp',
		'messageGetPassword':'mobile/messageGetPassword.jsp',
		'messageGetPassword2':'mobile/messageGetPassword2.jsp',
		'emailGetPassword2':'mobile/emailGetPassword2.jsp',
		'emailGetAccount':'mobile/emailGetAccount.jsp',
		'messageGetAccount':'mobile/messageGetAccount.jsp',
		'EbetAppWebGame':'gameEbetLoginNew.php',
		'history':'mobile/history.jsp',
		'accountCenter':'mobile/accountCenter.jsp',

		'info':'mobile/account/info.jsp',
        'password':'mobile/account/password.jsp',
        'bankHistory':'mobile/account/bankHistory.jsp',
		'securityProtection':'mobile/account/securityProtection.jsp',
		'payPassword':'mobile/account/payPassword.jsp',
		'messageNotice':'mobile/account/messageNotice.jsp',
		'bindBankCard':'mobile/bindBankCard.jsp',

        'agent':'mobile/agent.jsp',
        'agentHistory':'mobile/agentHistory.jsp',
        'agentWithdrawal':'mobile/agent/agentWithdrawal.jsp',
        'agentCenter':'mobile/agentCenter.jsp',
        'agentRegister':'mobile/agentRegister.jsp',
        'agentContact':'mobile/agentContact.jsp',
        'agentResult':'mobile/agent/agentResult.jsp',
        'agentInfo':'mobile/agent/agentInfo.jsp',
        'agentCreditLog':'mobile/agent/agentCreditLog.jsp',
        'agentPtCommission':'mobile/agent/agentPtCommission.jsp',
        'agentCommission':'mobile/agent/agentCommission.jsp',
        'agentSubuser':'mobile/agent/agentSubuser.jsp',
        'agentPlatform':'mobile/agent/agentPlatform.jsp',
        'agentBetprofit':'mobile/agent/agentBetprofit.jsp',
        'agentLink':'mobile/agent/agentLink.jsp',
        'agentCommission2':'mobile/agent/agentCommission2.jsp',
        'agentSubuser2':'mobile/agent/agentSubuser2.jsp',
        'agentCommissionGuide2':'mobile/agent/agentCommissionGuide2.jsp',
        'agentPtCommission2':'mobile/agent/agentPtCommission2.jsp',
		'agentInfoSet2':'mobile/agent/agentInfoSet2.jsp',
		'agentInfo2':'mobile/agent/agentInfo2.jsp',
		'deposit2':'mobile/agent/deposit2.jsp',
		'withdraw2':'mobile/agent/withdraw2.jsp',
		'backwater2':'mobile/agent/backwater2.jsp',
		'promotion2':'mobile/agent/promotion2.jsp',
		'winOrLose2':'mobile/agent/winOrLose2.jsp',
		'agentCreditLog2':'mobile/agent/agentCreditLog2.jsp',
		'password2':'mobile/account/password2.jsp',
		'modifyPassword2':'mobile/account/modifyPassword2.jsp',
		'addCard2':'mobile/agent/addCard2.jsp',
		'cardList2':'mobile/agent/cardList2.jsp',
		'suggestion2':'mobile/agent/suggestion2.jsp',
		'aliasName2':'mobile/agent/info/aliasName2.jsp',
		'actName2':'mobile/agent/info/actName2.jsp',
		'birthday2':'mobile/agent/info/birthday2.jsp',
		'email2':'mobile/agent/info/email2.jsp',
		'microchannel2':'mobile/agent/info/microchannel2.jsp',
		'phone2':'mobile/agent/info/phone2.jsp',
		'qq2':'mobile/agent/info/qq2.jsp',
		'agentWithdrawal2':'mobile/agent/agentWithdrawal2.jsp',
		'signBonus':'mobile/selfGet/signBonus.jsp'
	};
	
	//Storage Name
	var _storages = {
		'pcindex':'mobi-pcindex-storage',
		'common':'mobi-common-storage',
		'index':'mobi-index-storage',
		'login':'mobi-login-storage',
		'account':'mobi-account-storage',
		'fundsManage':'mobi-fundsManage-storage',
		'register':'mobi-register-storage',
		'modifyPassword':'mobi-modifyPassword-storage',
		'forgotPassword':'mobi-forgotPassword-storage',
		'forgotPassword2':'mobi-forgotPassword2-storage',
		'unlock':'mobi-unlock-storage',
		'forgotAccount':'mobi-forgotAccount-storage',
		'getAccount':'mobi-getAccount-storage',
		'license':'mobi-license-storage',
		'online800':'mobi-online800-storage',
		'emailGetPassword':'mobi-emailGetPassword-storage',
		'messageGetPassword':'mobi-messageGetPassword-storage',
		'emailGetPassword2':'mobi-emailGetPassword2-storage',
		'messageGetPassword2':'mobi-messageGetPassword2-storage',
		'emailGetAccount':'mobi-emailGetAccount-storage',
		'messageGetAccount':'mobi-messageGetAccount-storage',
		'QQ':'mobi-QQ-storage',
		'email':'mobi-email-storage',
		'selfGet':'mobi-selfGet-storag',
		'girls':'mobi-girl-storag',
		'cooperation':'mobi-cooperation-storag',
		'preferential':'mobi-preferential-storage',
		'question':'mobi-question-storage',
		'news':'mobi-news-storage',
		'bbs':'mobi-bbs-storage',
		'agent':'mobi-agent-storage',
		'agentHistory':'mobi-agentHistory-storage',
		'agentWithdrawal':'mobi-agentWithdrawal-storage',
		'accountHistory':'mobi-accountHistory-storage',
		'vip':'mobi-vip-storage',
		'history':'mobi-history-storage',
		'agentInfo2':'mobi-agentInfo2-storage',
        'singBonus':'mobi-singBonus-storage'
	};
	
	//轉址
	that.redirect = function(key,param,target){
		_redirect(key,param,target);
	};
	//Get Object from SessionStorage
	that.getSessionStorage = function(key){
		return getSessionStorage(key);
	};
	//set Object in SessionStorage
	that.setSessionStorage = function(key,param){
		setSessionStorage(key,param);
	};

	//Get Object from Storage
	that.getLocalStorage = function(key){
		return getLocalStorage(key);
	};
	//set Object in Storage
	that.setLocalStorage = function(key,param){
		setLocalStorage(key,param);
	};
	
	/**
	 * 取得验证码url
	 */
	that.getSecurityCodeUrl = function(){
		return _securityCodeUrl;
	};
	
	/**
	 * 
	 */
	that.getModel = function(){
		if(!_module){
			_module = new MUIModel(that,mui);
		}
		return _module;
	};
	
	/**
	 * get loader
	 */
	that.getLoader = function(){
		if(!_loader){
			if(!Loader||typeof Loader !== 'function'){
				alert('Loader 加载失败，请重新刷新页面。');
				return;
			}
			_loader = new Loader();
		}
		return _loader;
	};
	
	/**
	 * get UserManage
	 */
	that.getUserManage = function(){
		if(!_userManage){
			if(!UserManage||typeof UserManage !== 'function'){
				alert('UserManage 加载失败，请重新刷新页面。');
				return;
			}
			_userManage = new UserManage(_baseUrl);
		}
		return _userManage;
	};
	

	/**
	 * get TPPManage
	 */
	that.getTPPManage = function(){
		if(!_TPPManage){
			if(!TPPManage||typeof TPPManage !== 'function'){
				alert('TPPManage 加载失败，请重新刷新页面。');
				return;
			}
			_TPPManage = new TPPManage(_baseUrl);
		}
		return _TPPManage;
	};
	
	/**
	 * get BankManage
	 */
	that.getBankManage = function(){
		if(!_bankManage){
			if(!BankManage||typeof BankManage !== 'function'){
				alert('BankManage 加载失败，请重新刷新页面。');
				return;
			}
			_bankManage = new BankManage(_baseUrl);
		}
		return _bankManage;
	};
	
	/**
	 * get SelfGetManage
	 */
	that.getSelfGetManage = function(){
		if(!_selfGetManage){
			if(!SelfGetManage||typeof SelfGetManage !== 'function'){
				alert('BankManage 加载失败，请重新刷新页面。');
				return;
			}
			_selfGetManage = new SelfGetManage(_baseUrl);
		}
		return _selfGetManage;
	};

	/**
	 * get SignManage
	 */
	that.getSignManage = function(){
		if(!_signManage){
			if(!SignManage||typeof SignManage !== 'function'){
				alert('BankManage 加载失败，请重新刷新页面。');
				return;
			}
			_signManage = new SignManage(_baseUrl);
		}
		return _signManage;
	};

	/**
	 * get ExperienceManage 体验金
	 */
	that.getExperienceManage = function(){
		if(!_experienceManage){
			if(!ExperienceManage||typeof ExperienceManage !== 'function'){
				alert('ExperienceManage 加载失败，请重新刷新页面。');
				return;
			}
			_experienceManage = new ExperienceManage(_baseUrl);
		}
		return _experienceManage;
	};
	
	/**
	 * 点触
	 * @param {function} callback 回调函数
	 */
	that.openTouClick = function(callback){
		if(!TouClick){
			//透过jquery 动态加载 js 
			that.getLoader().open("点触验证初始化");
			$.getScript(_touClickUrl).done(function( script, textStatus ) {
				that.getLoader().close();
				if(TouClick){
					_startTouClick(callback);
				}else{
					callback({success:false,message:'点触验证加载失败，请刷新页面！'});
				}
			}).fail(function( jqxhr, settings, exception ) {
				that.getLoader().close();
				callback({success:false,message:'点触验证加载失败，请刷新页面！'});
			});
		}else{
			_startTouClick(callback);
		}
	};

	/**
	 * 中止ajax
	 */
	that.abortAjax = _abortAjax;

	/**
	 * 是否为webview开启
	 */
	that.isWebapp = function(){
		return sessionStorage['webapp']?JSON.parse(sessionStorage['webapp']):false;
	};
	
	_init();
	/**
	 * 初始化
	 */
	function _init(){
		/**
		 * 离开网页时，检查是否有正在运行的ajax
		 */
		$(window).bind('beforeunload',function(){
			if(_hasRunAjax()){
				return '目前尚有正在执行的动作，可能会造成资料异常，确定要离开？';
			}
		});
		
		/**
		 * 离开网页时，退出运行的ajax
		 */
		$(window).bind('unload',_abortAjax);
		
		_initUrlParamValue();
		
		if(!that.isWebapp()&&_urlParamValue['webapp']){
			sessionStorage['webapp'] = _urlParamValue['webapp'];
		}
	}


	/**
	 * 如果有ajax在运行的话，就退出运行的ajax
	 */
	function _abortAjax(){
		for(var key in _ajaxObj){
			if(_ajaxObj[key]&&_ajaxObj[key].abort){
				_ajaxObj[key].abort();
				_ajaxObj[key] = false;
			}
		}
	}

	/**
	 * 检查是否有正在运行的ajax
	 */
	function _hasRunAjax(){
		for(var key in _ajaxObj){
			if(_ajaxObj[key]&&_ajaxObj[key].abort){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * 统一使用 ajax
	 * @param {Object} config 资料 
	 * {
	 *		url:来源网址,
	 *		param:请求参数,
	 *		timeout:timeout ms,
	 *		callback:回调方法
	 *	}
	 */
	var _dataType = ['json'];
	that.ajax = function(config){
		//预设参数
		var _config = {
			url:false,
			param:{},
			type:'post',
			dataType:'json',
			timeout:false,
			callback:false
		};
		
		$.extend(_config,config);

		//统一回传讯息
		var _result = {
			success:false,
			message:''
		};
		//检查网址是否存在
		if(!_config.url){
			_result.message='来源网址不存在！';
			_executeCallBack(_result);
			return;
		}
		
		//检查返回资料格式
		if(_dataType.indexOf(_config.dataType)==-1){
			_result.message='不支持'+_config.dataType+'资料格式！';
			_executeCallBack(_result);
			return;
		}
		
		//避免重複執行
		if(_ajaxObj[_config.url]){
			_result.message='目前正在执行，请稍候再尝试！';
			_executeCallBack(_result);
			return;
		}
		_ajaxObj[_config.url] = true;
		
		//回調
		function _executeCallBack(result){
			if(typeof _config.callback ==='function'){
				_config.callback(result);
			}
		}
		_ajaxObj[_config.url] = $.ajax({
			type:_config.type,
			url:_config.url,
			data:_config.param,
			dataType:_config.dataType,
			timeout:_config.timeout,
			success:function(result){
				_ajaxObj[_config.url] = false;
				$.extend(_result,result);
				_executeCallBack(_result);
				_result = null;
			}
		}).fail(function(result) {
			_ajaxObj[_config.url] = false;
			_result.message = _getStatusText(result.status,result.statusText);
			if(_result.message){
				_executeCallBack(_result);
			}
			_result = null;
		});
		
		return _ajaxObj[_config.url];
	};
	
	/**
	 * 取得请求失败信息
	 * @param {String} statusCode HttpStatusCode
	 * @param {String} statusText status = 0 有不同的状况
	 * @return {String} text 对应的信息
	 */
	function _getStatusText(statusCode,statusText){
		var text = '错误代码：'+statusCode+' '+statusText;
		if(statusCode!=0){
			switch (statusCode){
				case 400://reload
					text = '當前请求无法理解！';
					break;
				case 403:
					text = '拒绝执行当前请求！';
					break;
				case 404:
					text = '网址不存在！';
					break;
				case 408 :
					text = '请求超时，请稍候再试！';
					break;
				case 500 :
					text = '发生无法预料错误！';
					break;
				case 502 :
					text = '请求无回应，请稍候再试！';
					break;
				case 504 :
					text = '请求超时，请稍候再试！';
					break;
			}
		}else{
			switch (statusText){
				case 'error'://reload
					text = '网路异常，请稍候再试！';
					break;
				case 'timeout':
					text = '请求超时，请稍候再试！';
					break;
				case 'abort':
//					text = '请求已中断！';
					text = false;
					break;
			}
		}
		return text;
	}

	/**
	 * 点触
	 * @param {function} callback 回调函数
	 */
	function _startTouClick(callback){
		if(!window.touClickObj){
			window.touClickObj = new TouClick('touclick-container',{
	            onSuccess: _touClickSuccess,
	            onError:_touClickError
			});
		}
		window.touClickObj.callback = callback;
		window.touClickObj.start();
	}
	
	/**
	 * 点触 成功回调
	 * @param {Object} obj 
	 */
	function _touClickSuccess(obj){
		window.touClickObj.close();
		if(!window.touClickObj.callback)return;
		window.touClickObj.callback({success:true,data:obj});
    	window.touClickObj.callback = false;
	}
	
	/**
	 * 点触 失败回调
	 * @param {Object} obj 
	 */
	function _touClickError(obj){
		window.touClickObj.close();
		if(!window.touClickObj.callback)return;
    	window.touClickObj.callback({success:false,data:obj,message:'点触验证加载失败，请刷新页面！'});
    	window.touClickObj.callback = false;
	}
	
	/**
	 * 轉址
	 * @param {String} key 转指目标
	 * @param {Object} param 提交参数
	 * @param {String} target 开启位置
	 */
	function _redirect(key,param,target){
		var action = _actions[key];
		if(!action){
			alert(key+' 不存在');
			return;
		}
		var url = _urls[action];
		if(!url){
			alert(key+' 不存在');
			return;
		}
		setSessionStorage(key,param);
		if(!/^(http|https):\/\//.test(url)){
			url = _baseUrl+url;
		}
		if(!target){
			window.location.href = url;
		}else if(target=='_blank '){
			window.open(url,target)
		}
		
		action = url = null;
	}
	
	/**
	 * get SessionStorage
	 */
	function getSessionStorage(key){
		if(!key)return false;

		var action = _actions[key];
		if(!action)
			alert(key+' 不存在');
			
		var name = _storages[action];
		var storage = sessionStorage[name];
		if(storage){
			return JSON.parse(storage);
		}else{
			return {};
		}
		action = name = storage = null;
	}
	
	/**
	 * set SessionStorage
	 */
	function setSessionStorage(key,param){
		if(!key)return;
		if(!param)return;

		var action = _actions[key];
		if(!action)
			alert(key+' 不存在');
		
		var name = _storages[action];
		var storage = sessionStorage[name];
		if(storage){
			var obj = JSON.parse(storage);
			$.extend(obj,param);
			sessionStorage[name] = JSON.stringify(obj);
		}else{
			sessionStorage[name] = JSON.stringify(param);
		}
		action = name = storage = null;
	}
	
	/**
	 * get LocalStorage
	 */
	function getLocalStorage(key){
		if(!key)return false;

		var action = _actions[key];
		if(!action)
			alert(key+' 不存在');
			
		var name = _storages[action];
		var storage = localStorage[name];
		if(storage){
			return JSON.parse(storage);
		}else{
			return {};
		}
		action = name = storage = null;
	}
	
	/**
	 * set LocalStorage
	 */
	function setLocalStorage(key,param){
		if(!key)return;
		if(!param)return;

		var action = _actions[key];
		if(!action)
			alert(key+' 不存在');
		
		var name = _storages[action];
		var storage = localStorage[name];
		if(storage){
			var obj = JSON.parse(storage);
			$.extend(obj,param);
			localStorage[name] = JSON.stringify(obj);
		}else{
			localStorage[name] = JSON.stringify(param);
		}
		action = name = storage = null;
	}
	
	/**
	 * 解析Url param内容
	 */
	function _initUrlParamValue() {
		_urlParamValue = {};
		var query = window.location.search.substring(1);
		if(query.length==0)return;
		var vars = query.split("&"),pair;
		for (var i=0;i<vars.length;i++) {
			pair = vars[i].split("=");
			_urlParamValue[pair[0]] = pair[1];
		}
		query = vars = pair = null;
	}
	
	/**
	 * 产生get参数值
	 * @returns {String}
	 */
	function _getLocationSearch(){
		var search = '?';
		var param = '{0}={1}&';
		for (var i in _urlParamValue) {
			search+=String.format(param,i,_urlParamValue[i]);
		}
		search = search.length==1?'':search.slice(0,search.length-1);
		return search; 
	}
}
