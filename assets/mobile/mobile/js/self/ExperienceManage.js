/**
 * 负责所有帐户相关的操作
 */

function ExperienceManage(base_url){
	var that = this;
	var _base_url = base_url;
	var _urlObj = {
		getPhoneAndCode:'mobi/getPhoneAndCode.php',
	    sendSmsCode:'mobi/experienceSendSmsCode.php',
	    getExperience:'mobi/submitExperience.php'
	};

	/**
	 * get action url
	 * @param {String} name actin name
	 */
	function getUrl(name){
		if(!_urlObj[name]){
			alert(name+' 路径不存在！');
			return;
		}
		return _base_url+_urlObj[name];
	};
	/**
	 * 发送简讯验证码到用户注册手机
	 * @param {Function} callback 回调
	 * @public
	 */
	that.sendSmsCodeToPhone = function(callback){
		mobileManage.openTouClick(function(tResult){
			if(tResult.success){
				mobileManage.getLoader().open("发送中");
				mobileManage.ajax({
					url:getUrl('sendSmsCode'),
					param:{
						checkAddress:tResult.data.checkAddress.toString(),
						checkKey: tResult.data.token,
						sid: tResult.data.sid
					},
					callback:function(result){
						mobileManage.getLoader().close();
						callback(result);
					}
				});
			}else{
				alert(tResult.message);
			}
		});
	};
	
	/**
	 * 查询要发送的验证码
	 * @public
	 * @param {Function} callback 回调
	 */
	that.getPhoneAndCode = function(callback){
		mobileManage.ajax({
			url:getUrl('getPhoneAndCode'),
			callback:callback
		});
	};
	
	
	/**
	 * 领取体验金
	 * @public
	 */
	that.getExperience = function(formData,callback){
		var _formData = {
			// imageCode:'',
			platform:''
		};
		$.extend(_formData,formData);
		//檢查
		var err = _experienceCheck(_formData);
		if(err){
			if(typeof callback === 'function'){
				callback({success:false,message:err});
			}
			return;
		}
		mobileManage.ajax({
			url:getUrl('getExperience'),
			param:_formData,
	    	callback:callback
	    });
	};
	

	//体验金领取验证
	function _experienceCheck(formData){
		if(!formData.platform){
			return '[提示]请选择转入平台！';
		}
		return undefined;
	}
}
