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
		<div class="text-uppercase"><?php echo $this->uri->segment(3);?> <?php echo $this->lang->line('page_faq_title');?></div>
	</div>
</header>

<div class="main-wrap" style="margin-top: 60px;">
	<div class="divprofile infos">		
		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_1');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_1');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_2');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_2');?></p>
		<p><?php echo $this->lang->line('page_faq_content_3');?></p>
		<p><?php echo $this->lang->line('page_faq_content_4');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_3');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_5');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_4');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_6');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_5');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_7');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_6');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_8');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_7');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_9');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_8');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_10');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_9');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_11');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_10');?></h5>
		<p><?php echo $this->lang->line('page_faq_content_12');?></p>

		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_11');?></h5>
		<p><b><?php echo $this->lang->line('page_faq_content_13');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_14');?></li>
		<li><?php echo $this->lang->line('page_faq_content_15');?></li>
		<li><?php echo $this->lang->line('page_faq_content_16');?></li>
		<li><?php echo $this->lang->line('page_faq_content_17');?></li>
		<li><?php echo $this->lang->line('page_faq_content_18');?></li>
		<br>
		<p><b><?php echo $this->lang->line('page_faq_content_19');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_20');?></li>
		<li><?php echo $this->lang->line('page_faq_content_21');?></li>
		<li><?php echo $this->lang->line('page_faq_content_22');?></li>
		<li><?php echo $this->lang->line('page_faq_content_23');?></li>
		<li><?php echo $this->lang->line('page_faq_content_24');?></li>
		<br>
		<p><b><?php echo $this->lang->line('page_faq_content_25');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_26');?></li>
		<li><?php echo $this->lang->line('page_faq_content_27');?></li>
		<li><?php echo $this->lang->line('page_faq_content_28');?></li>
		<li><?php echo $this->lang->line('page_faq_content_29');?></li>
		<br>
		<h5 class="text-danger"><?php echo $this->lang->line('page_faq_12');?></h5>
		<p><b><?php echo $this->lang->line('page_faq_content_30');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_31');?></li>
		<li><?php echo $this->lang->line('page_faq_content_32');?></li>
		<li><?php echo $this->lang->line('page_faq_content_33');?></li>
		<li><?php echo $this->lang->line('page_faq_content_34');?></li>
		<li><?php echo $this->lang->line('page_faq_content_35');?></li>
		<li><?php echo $this->lang->line('page_faq_content_36');?></li>
		<li><?php echo $this->lang->line('page_faq_content_37');?></li>
		<br>
		<p><b><?php echo $this->lang->line('page_faq_content_38');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_39');?></li>
		<li><?php echo $this->lang->line('page_faq_content_40');?></li>
		<li><?php echo $this->lang->line('page_faq_content_41');?></li>
		<li><?php echo $this->lang->line('page_faq_content_42');?></li>
		<li><?php echo $this->lang->line('page_faq_content_43');?></li>
		<br>
		<p><b><?php echo $this->lang->line('page_faq_content_44');?></b></p>
		<li><?php echo $this->lang->line('page_faq_content_45');?></li>
		<li><?php echo $this->lang->line('page_faq_content_46');?></li>
		<li><?php echo $this->lang->line('page_faq_content_47');?></li>
		<li><?php echo $this->lang->line('page_faq_content_48');?></li>	
		<p>&nbsp;</p>
		<p>&nbsp;</p>
		<p>&nbsp;</p>
	</div>
</div>



<?php $this->load->view('mobile/parts/footer');?>