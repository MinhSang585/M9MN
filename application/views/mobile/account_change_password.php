<?php $this->load->view('mobile/parts/header');?>

	<div class="col-12 p-5 text-white">
		<?php echo form_open('ajax/change_password', array('id' => 'change-password-form'));?>
			<div class="form-group">
				<label for="oldpass"><?php echo $this->lang->line('label_current_password');?></label>
				<input type="password" class="form-control" id="oldpass" name="oldpass" value="" maxlength="15">
			</div>
			<div class="form-group pt-2">
				<label for="password"><?php echo $this->lang->line('label_new_password');?></label>
				<input type="password" class="form-control" id="password" name="password" value="" maxlength="15">
			</div>
			<div class="form-group pt-2">
				<label for="passconf"><?php echo $this->lang->line('label_confirm_new_password');?></label>
				<input type="password" class="form-control" id="passconf" name="passconf" value="" maxlength="15">
			</div>
			<div class="form-group pt-2 text-center">
				<button type="submit" class="col-6 btn btn-primary"><?php echo $this->lang->line('lang_submit');?></button>
			</div>
		<?php echo form_close();?>
	</div>

<?php $this->load->view('mobile/parts/footer');?>