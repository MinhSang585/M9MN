<?php $this->load->view('mobile/parts/header');?>
	
	<div class="content-wrap">
	       
		<div class="gamelanding">
			<div class="slot-header">
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('GG');"  style="display:none;">
					<img src="<?php echo base_url('assets/ebv2/m/images/slots/top_nav_gg.png');?>">
				</a>
				<a href="javascript:void(0);" class="big-icon" onclick="sub_game('SG');"  style="display:none;">
					<img src="<?php echo base_url('assets/ebv2/m/images/slots/top_nav_sg.png');?>">
				</a>
			</div>
		</div>
		<div class="text-center pt-3">
			<h4><?php echo $this->uri->segment(3);?> <?php echo strtoupper($this->lang->line('page_fishing'));?></h4>
		</div>
		<div id="gameContent" class="slotpage-wrap clear">
			<?php echo (isset($list) ? $list : '');?>
		</div>
	</div>
	
<?php $this->load->view('mobile/parts/footer');?>