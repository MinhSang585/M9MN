<?php $this->load->view('mobile/parts/header');?>
	
	<div style="width: auto; margin:  auto; text-align:  center; padding-top: 58px;"> <img src="<?php echo base_url('assets/ebv2/m/images/sports-bg.jpg');?>"> </div>
	<div class="esportlanding" style="width: 100%;">
		<a href="javascript:void(0);" onclick="open_game('IBC', '<?php echo GAME_SPORTSBOOK;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/sport-maxbet.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('SBO', '<?php echo GAME_SPORTSBOOK;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/sport-maxbet.png');?>">
		</a>
		<a href="javascript:void(0);" onclick="open_game('CMD', '<?php echo GAME_SPORTSBOOK;?>');" class="big-icon">
			<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/sport-cmd.png');?>">
		</a>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>