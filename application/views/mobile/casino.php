<?php $this->load->view('mobile/parts/header');?>
	
	<div style="width: auto; margin:  auto; text-align:  center; padding-top: 58px;"> <img src="<?php echo base_url('assets/ebv2/m/images/casino-bg-v1.png');?>"> </div>
	<div class="gamelanding" style="padding-bottom: 60px;">
		<a href="javascript:void(0);" onclick="open_game('AB', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-allbet.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('DG', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-dg.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('MG', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-bbin.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('PT', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-playtech.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('SA', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-sa.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('SX', '<?php echo GAME_LIVE_CASINO;?>', 'MX-LIVE-002');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-aes.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('WM', '<?php echo GAME_LIVE_CASINO;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/live-wm.png');?>">
		</a>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>