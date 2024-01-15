/**
 * 
 */

function MobileComboBox(conf){
	var _this = this;
	var _conf = {
		appendId:'',
		cls:'',
		valueName:'',
		displayName:'',
		datas:'',
		onChange:function(){
			
		}
	};
	$.extend(_conf,conf);
	var _id = 'm-'+_conf.appendId;
	var _cboHtml = '<select class="{0}" id="'+_id+'">{1}</select>';
	var _cboOptionHtml = '<option value="{0}">{1}</option>';
	var _$cbo;
	var _items;
	var _selRecord;
	var _records;
	
	_this.getJq = function(){
		return _$cbo;
	};
	
	_this.getEl = function(){
		return _$cbo.get(0);
	};
	
	_this.getValue = function(){
		return _$cbo.val();
	};
	
	_this.setValue = function(value){
		_$cbo.val(value);
		_$cbo.trigger('change');
	};
	
	_this.disableAllItem = function(){
		for(var i = 0;i<_items.length;i++){
			$(_items[i]).attr('disabled',true);
		}
	};
	
	_this.enableAllItem = function(){
		for(var i = 0;i<_items.length;i++){
			$(_items[i]).attr('disabled',false);
		}
	};
	
	_this.disableItemByValue = function(value){
		for(var i = 0;i<_items.length;i++){
			if(_items[i].value==value){
				$(_items[i]).attr('disabled',true);
				if(_$cbo.val()==value){
					_$cbo.val('');
				}
				return;
			}
		}
	};
	
	_this.enableItemByValue = function(value){
		for(var i = 0;i<_items.length;i++){
			if(_items[i].value==value){
				$(_items[i]).attr('disabled',false);
				return;
			}
		}
	};
	
	_this.loadData = function(datas){
		_conf.datas = datas;
		_$cbo.html(_createOptHtml());
		_items = _$cbo.children();
	};
	
	_this.getRecord = function(){
		return _records[_this.getValue()];
	};
	
	function _init(){
		if($('#'+_conf.appendId).get(0)){
			if($('#'+_id).get(0))
				$('#'+_id).remove();
			$('#'+_conf.appendId).replaceWith(_createComboBoxHtml());
			_$cbo = $('#'+_id);
			_items = _$cbo.children();
			_$cbo.change(_onChange);
		}else{
			alert(String.format('{0} element not exit',_conf.appendId));
		}
	}
	
	function _onChange(e){
		_conf.onChange(e);
	}
	
	function _createComboBoxHtml(){
		return String.format(_cboHtml,_conf.cls,_createOptHtml());
	}
	
	function _createOptHtml(){
		_records = {};
		var ops = '';
		for(var i = 0 ;i<_conf.datas.length;i++){
			var v = _conf.datas[i][_conf.valueName];
			_records[_conf.datas[i][_conf.valueName]] = _conf.datas[i];
			ops+=String.format(_cboOptionHtml,_conf.datas[i][_conf.valueName],_conf.datas[i][_conf.displayName]);
		}
		return ops;
	}
	_init();
}