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
		<div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('page_tnc');?></div>
	</div>
</header>

<div class="main-wrap" style="margin-top: 60px;">
	<div class="divprofile infos">
		<p><small><?php echo $this->lang->line('page_terms_content_1');?><br><?php echo $this->lang->line('page_terms_content_2');?></small></p>
		<p><?php echo $this->lang->line('page_terms_content_3');?></p>
		<p><?php echo $this->lang->line('page_terms_content_4');?></p>
		<p><?php echo $this->lang->line('page_terms_content_5');?></p>
		<p><?php echo $this->lang->line('page_terms_content_6');?></p>
		<p><?php echo $this->lang->line('page_terms_content_7');?></p>
		<p><?php echo $this->lang->line('page_terms_content_8');?></p>
		<p><?php echo $this->lang->line('page_terms_content_9');?></p>
		<p><?php echo $this->lang->line('page_terms_content_10');?></p>
		<p><?php echo $this->lang->line('page_terms_content_11');?></p>
		<p><?php echo $this->lang->line('page_terms_content_12');?></p>
		<p><?php echo $this->lang->line('page_terms_content_13');?></p>
		<p><?php echo $this->lang->line('page_terms_content_14');?></p>
		<p><?php echo $this->lang->line('page_terms_content_15');?></p>
		<p><?php echo $this->lang->line('page_terms_content_16');?></p>
		<p><?php echo $this->lang->line('page_terms_content_17');?></p>
		<p><?php echo $this->lang->line('page_terms_content_18');?></p>
		<p><?php echo $this->lang->line('page_terms_content_19');?></p>
		<br>
		<h3 class="mb-1"><?php echo $this->lang->line('page_terms_2');?></h3>
		<p><?php echo $this->lang->line('page_terms_content_20');?></p>
		<p><?php echo $this->lang->line('page_terms_content_21');?></p>
		<p><?php echo $this->lang->line('page_terms_content_22');?></p>
		<p><?php echo $this->lang->line('page_terms_content_23');?></p>
		<p><?php echo $this->lang->line('page_terms_content_24');?></p>
		<p><?php echo $this->lang->line('page_terms_content_25');?></p>
		<p><?php echo $this->lang->line('page_terms_content_26');?></p>
		<p><?php echo $this->lang->line('page_terms_content_27');?></p>
		<p><?php echo $this->lang->line('page_terms_content_28');?></p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	</div>
</div>

<?php $this->load->view('mobile/parts/footer');?>