<script type="text/javascript">
	function filter_sport(code) {
		var path = "<?php echo base_url(); ?>assets/desktop/images/new/sport/";

		if(code=="ibc") {			
			$( "#sport-cmd" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#sport-sbo" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#sport-ibc" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-ibc').attr('src',path+'btn_sport_ibc_on.png');
			
			$('#img-cmd').attr('src',path+'btn_sport_cmd_off.png');
			$('#img-sbo').attr('src',path+'btn_sport_sbo_off.png');			
		}
		else if(code=="sbo") {
			$( "#sport-cmd" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#sport-ibc" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#sport-sbo" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-sbo').attr('src',path+'btn_sport_sbo_on.png');
			
			$('#img-cmd').attr('src',path+'btn_sport_cmd_off.png');
			$('#img-ibc').attr('src',path+'btn_sport_ibc_off.png');			
		}		
		else {
			$( "#sport-sbo" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#sport-ibc" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#sport-cmd" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-cmd').attr('src',path+'btn_sport_cmd_on.png');
			
			$('#img-sbo').attr('src',path+'btn_sport_sbo_off.png');
			$('#img-ibc').attr('src',path+'btn_sport_ibc_off.png');			
		}
	}
</script>