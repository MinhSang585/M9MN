<?php $this->load->view('web/parts/header');?>

	<div class="container-fluid mainbanner p-0" style="background:#000;">
		<div class="container p-0">
			<div class="row no-gutters">
				<div class="col-12">
					<div class="sub-about-wrap">
						<?php $this->load->view('web/parts/side_menu');?>
						<div class="sub-about-right">
							<div class="sub-about-title">
								<h2 class="m-2"><?php echo $this->lang->line('page_how_to_join_1');?></h2>
							</div>
							<div class="sub-about-con">
								<p><?php echo $this->lang->line('page_how_to_join_content_1');?></p>
								
								<p><?php echo $this->lang->line('page_how_to_join_content_2');?></p>
								
								<p><?php echo $this->lang->line('page_how_to_join_content_3');?></p>
								
								<p><?php echo $this->lang->line('page_how_to_join_content_4');?></p>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<div>

<?php $this->load->view('web/parts/footer');?>