<?php $this->load->view('mobile/parts/header');?>
	
	<div class="gamelanding gamelanding-slots">
		<div class="gamelanding-slot-wrap">
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('CQ9', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-spade.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('JDB', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-spade.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('JK', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-spade.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('MG', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-microgaming.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('PP', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-playtech.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('PT', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-playtech.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('SP', '<?php echo GAME_SLOTS;?>');">
				<img src="<?php echo base_url('assets/ebv2/m/images/MY/' . get_language_folder() . '/home-page/slot-betsoft.png');?>">
			</a>
		</div>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>