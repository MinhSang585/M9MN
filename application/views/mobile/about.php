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
		<div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('page_about_us_1');?></div>
	</div>
</header>

<div class="main-wrap" style="margin-top: 60px;">
	<div class="divprofile infos text-dark">
		<?php echo $this->lang->line('page_about_us_content_1');?><br /><br />
		<?php echo $this->lang->line('page_about_us_content_2');?><br /><br />
		<b><?php echo $this->lang->line('page_about_us_2');?></b><br /><br />
		<?php echo $this->lang->line('page_about_us_content_3');?><br /><br />
		<b><?php echo $this->lang->line('page_about_us_3');?></b><br /><br />
		<?php echo $this->lang->line('page_about_us_content_4');?><br /><br />
		<?php echo $this->lang->line('page_about_us_content_5');?><br /><br />

	</div>
</div>

<?php $this->load->view('mobile/parts/footer');?>