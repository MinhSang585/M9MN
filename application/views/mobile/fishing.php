<?php $this->load->view('mobile/parts/header');?>
	
	<div class="gamelanding gamelanding-slots">
		<div class="gamelanding-slot-wrap">
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('PT');">
				<img src="<?php echo base_url('assets/ebv2/m/images/slots/gamebanner_pt.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('MG');">
				<img src="<?php echo base_url('assets/ebv2/m/images/slots/gamebanner_mg.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('JK');">
				<img src="<?php echo base_url('assets/ebv2/m/images/slots/gamebanner_jk.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('JDB');" style="display: none;">
				<img src="<?php echo base_url('assets/ebv2/m/images/slots/gamebanner_jdb.png');?>">
			</a>
			<a href="javascript:void(0);" class="big-icon" onclick="sub_game('SG');">
				<img src="<?php echo base_url('assets/ebv2/m/images/slots/gamebanner_sg.png');?>">
			</a>
		</div>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>