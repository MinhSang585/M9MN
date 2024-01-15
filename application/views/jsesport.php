<script type="text/javascript">
	function filter_esport(code) {
		var path = "<?php echo base_url(); ?>assets/desktop/images/new/esport/";

		if(code=="ae") {			
			$( "#esport-lh" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#esport-sbo" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#esport-sxes" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-sxes').attr('src',path+'btn_esport_ae_on.png');
			
			$('#img-lh').attr('src',path+'btn_esport_tf_off.png');
			$('#img-sbo').attr('src',path+'btn_esport_sbo_off.png');			
		}
		else if(code=="sbo") {
			$( "#esport-lh" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#esport-sxes" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#esport-sbo" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-sbo').attr('src',path+'btn_esport_sbo_on.png');
			
			$('#img-lh').attr('src',path+'btn_esport_tf_off.png');
			$('#img-sxes').attr('src',path+'btn_esport_ae_off.png');			
		}		
		else {
			$( "#esport-sbo" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#esport-sxes" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);			
			
			$( "#esport-lh" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);			
			$('#img-lh').attr('src',path+'btn_esport_tf_on.png');
			
			$('#img-sbo').attr('src',path+'btn_esport_sbo_off.png');
			$('#img-sxes').attr('src',path+'btn_esport_ae_off.png');			
		}
	}
</script>