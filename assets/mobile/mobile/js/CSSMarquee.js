/**
 * 使用 CSS Animation 设计的跑马灯
 */
function CSSMarquee(config){
	var that = this;
	var _config = {
		id:false,
		speed:40
	};
	
	$.extend(_config,config)
	var _$marquee = false;
	var _$content = false;
	
	that.start = _start;
	that.stop = _stop;
	
	function _init(){
		if(_$marquee)return;
			
		_$marquee = $('#'+_config.id);
		if(!_$marquee[0]){
			alert('Element 不存在！');
			return;
		}
		_$content = _$marquee.find('.marquee-content');
		var width = _$marquee.height()>_$content.width()?_$marquee.width():_$content.width() - 100;
		
		_$content.css('animation-duration',((width*_config.speed)/1000)+'s');
	}
	
	function _start(){
		_$content.removeClass('paused');
	}
	
	function _stop(){
		_$content.addClass('paused');
	}
	
	_init();
}