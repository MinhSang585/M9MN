<div class="sub-side-nav">
	<ul>
		<li><a href="<?php echo site_url('about');?>" <?php echo (($this->uri->segment(1) == 'about') ? 'class="active"' : '');?>><?php echo $this->lang->line('page_about_us');?></a></li>
		<li><a href="<?php echo site_url('faq');?>" <?php echo (($this->uri->segment(1) == 'faq') ? 'class="active"' : '');?>><?php echo $this->lang->line('page_faq');?></a></li>
		<li><a href="<?php echo site_url('banking');?>" <?php echo (($this->uri->segment(1) == 'banking') ? 'class="active"' : '');?>><?php echo $this->lang->line('banking_information');?></a></li>
		<li><a href="<?php echo site_url('contact');?>" <?php echo (($this->uri->segment(1) == 'contact') ? 'class="active"' : '');?>><?php echo $this->lang->line('label_contact_us');?></a></li>
		<?php /*
		<li><a href="<?php echo site_url('howtojoin');?>" <?php echo (($this->uri->segment(1) == 'howtojoin') ? 'class="active"' : '');?>><?php echo $this->lang->line('how_to_join');?></a></li> */ ?>
		<li><a href="<?php echo site_url('terms');?>" <?php echo (($this->uri->segment(1) == 'terms') ? 'class="active"' : '');?>><?php echo $this->lang->line('page_tnc');?></a></li>
		<?php /*
		<li><a href="<?php echo site_url('help');?>" <?php echo (($this->uri->segment(1) == 'help') ? 'class="active"' : '');?>><?php echo $this->lang->line('label_help');?></a></li>
		*/ ?>
	</ul>
</div>