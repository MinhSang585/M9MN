<?php $this->load->view('mobile/parts/header');?>

<style>
	header{height: 40px !important;box-shadow: 0 5px 10px #ccd7e6 !important;color:#6c6e71 !important;background:#fff !important;}
	.lr{display:none;}
	.m-bottom-menu{border-top:none;box-shadow: 0 -5px 8px -3px #ccd7e6;}
	.profile_title{text-align:center;line-height:40px;font-size:19px;}

	#comm-back-button {
		background: url(<?php echo base_url('assets/mobile/images/icon-back.png'); ?>) no-repeat;
		z-index: 99;
		width: 23px;
		height: 23px;
		background-size: 50%;
		position: absolute;
		left: 6px;
		top: 10px;
		filter: invert(60%);
	}

</style>

<header id="common-header" class="common-header" style="display: block;">
	<div class="cont relative profile_title">
		<a href="<?php echo site_url(); ?>"><div id="comm-back-button" class="left-button cursor_pointer" style=""></div></a>
		<div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('label_contact_us');?></div>
	</div>
</header>

<div class="main-wrap" style="margin-top: 60px;">
	<div class="divprofile infos text-start text-dark">
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

<?php $this->load->view('mobile/parts/footer');?>