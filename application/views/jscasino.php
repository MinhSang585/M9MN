<script type="text/javascript">
	function filter_casino(code) {
		var path = "<?php echo base_url(); ?>assets/desktop/images/new/casino/";
		if(code=="bbin") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-bbin').attr('src',path+'btn_live_bbin_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="dg") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-dg').attr('src',path+'btn_live_dg_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="pt") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-pt').attr('src',path+'btn_live_pt_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="mg") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-mg').attr('src',path+'btn_live_mg_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="pp") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-pp').attr('src',path+'btn_live_pp_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="sa") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-sa').attr('src',path+'btn_live_sa_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="sx") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-sx').attr('src',path+'btn_live_aesexy_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="wm") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-wm').attr('src',path+'btn_live_wm_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
		else if(code=="yb") {
			$( "#live-ab" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ae" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-yb').attr('src',path+'btn_live_yeebet_on.png');
			$('#img-allbet').attr('src',path+'btn_live_allbet_off.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sx').attr('src',path+'btn_live_aesexy_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
		}
		else {
			$( "#live-bbin" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-dg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pt" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-mg" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-pp" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-sa" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-wm" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-yb" ).removeClass( "d-block" ).addClass( "d-none" ).fadeOut(0).fadeIn(1000);
			$( "#live-ab" ).removeClass( "d-none" ).addClass( "d-block" ).fadeOut(0).fadeIn(1000);
			$('#img-allbet').attr('src',path+'btn_live_allbet_on.png');
			$('#img-bbin').attr('src',path+'btn_live_bbin_off.png');
			$('#img-dg').attr('src',path+'btn_live_dg_off.png');
			$('#img-pt').attr('src',path+'btn_live_pt_off.png');
			$('#img-mg').attr('src',path+'btn_live_mg_off.png');
			$('#img-pp').attr('src',path+'btn_live_pp_off.png');
			$('#img-sa').attr('src',path+'btn_live_sa_off.png');
			$('#img-wm').attr('src',path+'btn_live_wm_off.png');
			$('#img-yb').attr('src',path+'btn_live_yeebet_off.png');
		}
	}
</script>