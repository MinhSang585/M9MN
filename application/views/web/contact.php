<?php $this->load->view('web/parts/header');?>
<style>
.contactus-icon {
	width: 50px;
	margin-right: 10px;
	float: left;
	text-align: center;
	font-size: 9px;
}
.contactus-img-wrap {
	width: 40px;
	height: 40px;
	margin: 10px auto;
	display: flex;
	align-items: center;
	justify-content: center;
}
</style>
	<div class="container-fluid mainbanner p-0" style="background:#f8f9fe;">
		<div class="container p-0">
			<div class="row no-gutters">
				<div class="col-12">
					<div class="sub-about-wrap">
						<?php $this->load->view('web/parts/side_menu');?>
						<div class="sub-about-right">
							<div class="sub-about-title">
								<h2 class="m-2"><?php echo $this->lang->line('label_contact_us');?></h2>
							</div>
							<div class="sub-about-con">
								<p class="pb-3"><?php echo $this->lang->line('label_contact_notice_1');?> <?php echo $this->lang->line('label_contact_notice_2');?></p>
								<?php if( ! empty($contact[16]['value'])):?>
								<p class="pb-3"><?php echo $this->lang->line('im_tel');?> : <?php echo $contact[16]['value'];?> (12:00:00am - 11:59:59pm)</p>
								<?php endif;?>
								<?php if( ! empty($contact[15]['value'])):?>
								<p class="pb-3"><?php echo $this->lang->line('im_email');?> : <a href="mailto:<?php echo $contact[15]['value'];?>"><?php echo $contact[15]['value'];?></a></p>
								<?php endif;?>
								<?php if( ! empty($contact[2]['value'])):?>
								<p class="pb-3"><?php echo $this->lang->line('im_wechat');?> : <?php echo $contact[2]['value'];?></p>
								<?php endif;?>
								<p class="pb-3"><?php echo $this->lang->line('label_website');?> :</p>
								<p class="pb-3">
									<a class="text-dark" href="<?php echo base_url();?>"><?php echo base_url();?></a>
								</p>
								<a href="https://direct.lc.chat/13335741/" target="_blank" class="text-dark">
									<div class="contactus-icon">
										<div class="contactus-img-wrap">
											<!-- <img src="<?php echo base_url('assets/ebv2/images/cs-livechat.png');?>" style="width: 100%;"> -->
											<i class="fas fa-users" style="font-size: 50px;"></i>
										</div>
										<p><?php echo $this->lang->line('label_live_chat_24_hours');?></p>
									</div>
								</a>
								<?php if( ! empty($contact[11]['value'])):?>
								<a href="<?php echo $contact[11]['value'];?>" target="_blank">
									<div class="contactus-icon">
										<div class="contactus-img-wrap">
											<img src="<?php echo base_url('assets/ebv2/images/MY/' . get_language_folder() . '/contact-page/cs-facebook.png');?>" style="width: 100%;">
										</div>
										<p><?php echo $this->lang->line('system_name')?></p>
									</div>
								</a>
								<?php endif;?>
								<?php if( ! empty($contact[12]['value'])):?>
								<a href="<?php echo $contact[12]['value'];?>" target="_blank">
									<div class="contactus-icon">
										<div class="contactus-img-wrap">
											<img src="<?php echo base_url('assets/ebv2/images/MY/' . get_language_folder() . '/contact-page/cs-ig.png');?>" style="width: 100%;">
										</div>
										<p><?php echo $this->lang->line('system_name')?></p>
									</div>
								</a>
								<?php endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php $this->load->view('web/parts/footer');?>