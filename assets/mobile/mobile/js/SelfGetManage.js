/**
 * 自助优惠管理
 */
function SelfGetManage(base_url){
	var that = this;
	var _base_url = base_url;
	
	var _youhuiDataUrl = _base_url+'mobi/getYouhuiData.php';
	var _doYouhuiUrl = _base_url+'mobi/doYouhui.php';
	var _ximaDataUrl = _base_url+'mobi/getXimaData.php';
	var _bigBangDataUrl = _base_url+'mobi/queryPTBigBang.php';
	var _getPTBigBangBonusUrl = _base_url+'mobi/getPTBigBangBonus.php';
	var _optLosePromoUrl = _base_url+'mobi/optLosePromo.php';
	var _doXimaUrl = _base_url+'mobi/doXima.php';
	var _transferInforCouponUrl = _base_url+'mobi/transferInforCoupon.php';
	var _transferInforRedCouponUrl = _base_url+'mobi/transferInforRedCoupon.php';
	var _optWeekSentUrl = _base_url+'mobi/optWeekSent.php';
	var _getPointsUrl = _base_url+'mobi/queryPoints.php';
	var _transferPointsUrl = _base_url+'mobi/transferPoints.php';
	var _queryFriendBonueUrl = _base_url+'mobi/queryfriendBonue.php';
	var _transferFriendUrl = _base_url+'mobi/transferInforFriend.php';
	var _getAppPreferential = _base_url+'mobi/getAppPreferential.php'; 
	
	/**
	 * 取得优惠配置
	 * @param {Function} 回调
	 */
	that.getYouhuiData = function(callback){
		mobileManage.ajax({
			url:_youhuiDataUrl,
			callback:callback
		});
	};
	
	/**
	 * 取得洗码资料
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.getXimaData = function(formData,callback){
		var _formData = {
			gameId:''
		};
		
		$.extend(_formData,formData);
		//检查
		var err = _ximaDataCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_ximaDataUrl,
			param:_formData,
			callback:callback
		});
	};

	/**
	 * 执行存送
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.doYouhui = function(formData,callback){
		var _formData = {
			youhuiType:false,
			money:false
		};
		$.extend(_formData,formData);
		//检查
		var err = _doYouhuiCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_doYouhuiUrl,
			param:_formData,
			callback:callback
		});
	};
	
	/**
	 * 执行洗码
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.executeXima = function(formData,callback){
		var _formData = {
			gameId:'',
			startDate:'',
			endDate:'',
			bet:''
		};
		$.extend(_formData,formData);
		//检查
		var err = _executeXimaCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_doXimaUrl,
			param:_formData,
			callback:callback
		});
	};
	
	/**
	 * 取得PT大爆炸资料
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.getBigBangData = function(formData,callback){
		mobileManage.ajax({
			url:_bigBangDataUrl,
			param:formData,
			callback:callback
		});
	};
	
	/**
	 * 领取PT大爆炸
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.getPTBigBangBonus = function(formData,callback){
		var _formData = {
			ptBigBangId:''
		};
		$.extend(_formData,formData);
		//检查
		var err = _bigBangBonusCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_getPTBigBangBonusUrl,
			param:_formData,
			callback:callback
		});
	};
	
	/**
	 * 领取救援金
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.getLosePromo = function(formData,callback){
		var _formData = {
			pno:'',
			platform:'',
			flag:'2'
		};
		$.extend(_formData,formData);
		//检查
		var err = _losePromoCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_optLosePromoUrl,
			param:_formData,
			callback:callback
		});
	};
	
	/**
	 * 取消救援金
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.cancelLosePromo = function(formData,callback){
		var _formData = {
			pno:'',
			flag:'-1'
		};
		$.extend(_formData,formData);
		//检查
		var err = _losePromoCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_optLosePromoUrl,
			param:_formData,
			callback:callback
		});
	};

	/**
	 * 优惠卷
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.transferInforCoupon = function(formData,callback){
		var _formData = {
			couponRemit:'',
			platform:'',
			couponCode:''
		};
		$.extend(_formData,formData);
		//检查
		var err = _transferInforCouponCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_transferInforCouponUrl,
			param:_formData,
			callback:callback
		});
	};
	

	/**
	 * 红包优惠卷
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.transferInforRedCoupon = function(formData,callback){
		var _formData = {
			platform:'',
			couponCode:''
		};
		$.extend(_formData,formData);
		//检查
		var err = _transferInforRedCouponCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_transferInforRedCouponUrl,
			param:_formData,
			callback:callback
		});
	};

	/**
	 * 领取周周回馈
	 * @param {Object} 提交资料
	 * @param {Function} 回调
	 */
	that.getWeekSent = function(formData,callback){
		var _formData = {
			pno:'',
			platform:'',
			flag:'2'
		};
		$.extend(_formData,formData);
		//检查
		var err = _weekSentCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_optWeekSentUrl,
			param:_formData,
			callback:callback
		});
	};
	

	/**
	 * 取得剩余积分
	 * @param {Function} callback 回调方法
	 */
	that.getPoints = function(callback){
		mobileManage.ajax({
			url:_getPointsUrl,
			callback:callback
		});
	};
	

	/**
	 * 积分转账
	 * @param {Object} formData 提交资料
	 * @param {Function} callback 回调方法
	 */
	that.transferPoints = function(formData,callback){
		var _formData = {
			money:false
		};
		
		$.extend(_formData,formData);
		//检查
		var err = _transferPointsCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_transferPointsUrl,
			param:_formData,
			callback:callback
		});
	};
	

	/**
	 * 取得剩余
	 * @param {Function} callback 回调方法
	 */
	that.queryFriendBonue = function(callback){
		mobileManage.ajax({
			url:_queryFriendBonueUrl,
			callback:callback
		});
	};
	

	/**
	 * 好友分享转帐
	 * @param {Object} formData 提交资料
	 * @param {Function} callback 回调方法
	 */
	that.transferFriend = function(formData,callback){
		var _formData = {
			signType:false,
			signRemit:false,
		    pno:false
		};

		$.extend(_formData,formData);
		//检查
		// var err = _transferFriendCheck(_formData);
		// if(err){
		// 	if(typeof callback === 'function'){
		// 		callback({success:false,message:err});
		// 	}
		// 	return;
		// }
		mobileManage.ajax({
			url:_transferFriendUrl,
			param:_formData,
			callback:callback
		});
	};
	
	/**
	 * 领取app下载彩金 
	 * @public
	 */
	that.getAppPreferential = function(formData,callback){
		var _formData = {
			imageCode:'',
			platform:''
		};
		$.extend(_formData,formData);
		//檢查
		var err = _appPrefertialCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:_getAppPreferential,
			param:_formData,
	    	callback:callback
	    });
	};
	
	//app下载彩金检查
	function _appPrefertialCheck(formData){
		if(!formData.platform){
			return '[提示]请选择转入平台！';
		}
		return undefined;
	}
	
	//检查
	function _ximaDataCheck(formData){
		if(!formData.gameId){
			return '[提示]请选择游戏平台！';
		}
		return false;
	}

	//检查
	function _executeXimaCheck(formData){
		if(!formData.gameId){
			return '[提示]请选择游戏平台！';
		}
		if(!formData.startDate){
			return '[提示]起始时间有误！';
		}
		if(!formData.endDate){
			return '[提示]截止时间有误！';
		}
		if(!formData.bet){
			return '无投注记录！';
		}
		return false;
	}
	
	//检查
	function _bigBangBonusCheck(formData){
		if(!formData.ptBigBangId){
			return '[提示]请选择领取礼金！';
		}
		return false;
	}

	//检查
	function _losePromoCheck(formData){
		if(!formData.pno){
			return '[提示]请选择救援金！';
		}
		if(formData.flag!=-1&&!formData.platform){
			return '[提示]请选择老虎机平台！';
		}
		return false;
	}
	

	//检查
	function _weekSentCheck(formData){
		if(!formData.pno){
			return '[提示]请选择一笔回馈！';
		}
		if(formData.flag!=-1&&!formData.platform){
			return '[提示]请选择老虎机平台！';
		}
		return false;
	}
	

	//检查
	function _transferInforCouponCheck(formData){
		if(!formData.platform){
			return '[提示]请选择游戏平台！';
		}
		if(!formData.couponCode){
			return '[提示]优惠代码不能为空！';
		}
		if(!formData.couponRemit){
			return '[提示]请输入存款金额！';
		}
		return false;
	}

	//检查
	function _transferInforRedCouponCheck(formData){
		if(!formData.platform){
			return '[提示]请选择游戏平台！';
		}
		if(!formData.couponCode){
			return '[提示]优惠代码不能为空！';
		}
		return false;
	}
	
	//检查
	function _doYouhuiCheck(formData){
		if(!formData.youhuiType){
			return '[提示]请选择优惠类型！';
		}
		if(!formData.money){
			return '[提示]请输入转帐金额！';
		}
		return false;
	}

	function _transferPointsCheck(formData){
		if(!formData.money){
			return '[提示]请输入兑换金额！';
		}
		if(!/^[0-9]+$/.test(formData.money)){
			return '兑换金额必须为整数！';
		}
		return false;
	}

	function _transferFriendCheck(formData){
		if(!formData.signType){
			return '[提示]请选择游戏平台！';
		}
		if(!formData.signRemit){
			return '[提示]请输入转账金额！';
		}
		// if(!/^[0-9]+$/.test(formData.money)){
		// 	return '兑换金额必须为整数！';
		// }
		return false;
	}
}