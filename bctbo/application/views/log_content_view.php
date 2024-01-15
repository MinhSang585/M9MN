<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body>
	<div class="wrapper">
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid mt-2">
				<div class="row">
					<!-- left column -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-primary">
							<!-- form start -->
							<div class="card-body">
								<div class="form-group row">
									<label for="new_data" class="col-5 col-form-label"><?php echo $this->lang->line('label_new_data');?></label>
								</div>
								<?php if(empty($log_content['new_data'])){ ?>
								<div class="form-group row">
									<label for="empty_new_data" class="col-5 col-form-label">-</label>
								</div>
								<?php }else{
									$new_data_content = json_decode($log_content['new_data'],true);
								?>
								<?php if($log_content['log_type'] == LOG_LOGIN):?>
									<?php if(isset($new_data_content['last_login_date'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_last_login_date');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo date("Y-m-d H:i:s",$new_data_content['last_login_date']);?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['login_token'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_login_token');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['login_token'];?></label>
										</div>
									</div>
									<?php endif;?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_LOGOUT):?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_CHANGE_PASSWORD):?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_MISCELLANEOUS_UPDATE):?>
									<?php if(isset($new_data_content['system_type'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $this->lang->line(get_system_type($new_data_content['system_type']));?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['min_deposit'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_min_deposit');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['min_deposit'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['max_deposit'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_max_deposit');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['max_deposit'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['min_withdrawal'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_min_withdrawal');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['min_withdrawal'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['max_withdrawal'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_max_withdrawal');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['max_withdrawal'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['win_loss_suspend_limit'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_win_loss_suspend_limit');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['win_loss_suspend_limit'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['deposit_sound'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_sound_mp3');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo $new_data_content['deposit_sound'];?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['is_deposit_sound'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_sound');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal <?php echo ((isset($new_data_content['is_deposit_sound']) && $new_data_content['is_deposit_sound'] == STATUS_ACTIVE)? 'text-success' : 'text-secondary');?>"><?php echo ((isset($new_data_content['is_deposit_sound']) && $new_data_content['is_deposit_sound'] == STATUS_ACTIVE)? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
										</div>
									</div>
									<?php endif;?>
									<?php if(isset($new_data_content['is_deposit_notice'])):?>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_notice');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal <?php echo ((isset($new_data_content['is_deposit_notice']) && $new_data_content['is_deposit_notice'] == STATUS_ACTIVE)? 'text-success' : 'text-secondary');?>"><?php echo ((isset($new_data_content['is_deposit_notice']) && $new_data_content['is_deposit_notice'] == STATUS_ACTIVE)? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
										</div>
									</div>
									<?php endif;?>
									
								<?php endif;?>
								<?php } ?>
								<!--
								<div class="form-group row">
									<label for="promotion_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_promotion_name');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['promotion_name']) ? $promotion['promotion_name'] : '-');?></label>
									</div>
								</div>
								-->
							</div>
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
					<div class="col-12">
						<!-- jquery validation -->
						<div class="card card-secondary">
							<!-- form start -->
							<div class="card-body">
								<div class="form-group row">
									<label for="old_data" class="col-5 col-form-label"><?php echo $this->lang->line('label_old_data');?></label>
								</div>
								<?php if(empty($log_content['old_data'])){ ?>
								<div class="form-group row">
									<label for="empty_old_data" class="col-5 col-form-label">-</label>
								</div>
								<?php }else{
									$old_data_content = json_decode($log_content['old_data'],true);
								?>
								<?php if($log_content['log_type'] == LOG_LOGIN):?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_LOGOUT):?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_CHANGE_PASSWORD):?>
								<?php endif;?>
								<?php if($log_content['log_type'] == LOG_MISCELLANEOUS_UPDATE):?>

								<?php endif;?>
								<?php } ?>
								<!--
								<div class="form-group row">
									<label for="promotion_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_old_data');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($promotion['promotion_name']) ? $promotion['promotion_name'] : '-');?></label>
									</div>
								</div>
								-->
							</div>
						</div>
						<!-- /.card -->
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {
			$(function () {
		    // Summernote
		    $('.summernote').summernote({
		    	height: 300,
		    })

		    // CodeMirror
		    CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
		      mode: "htmlmixed",
		      theme: "monokai"
		    });
		  })
			var is_allowed = true;
			
			bsCustomFileInput.init();
			
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});
		});
	</script>
</body>
</html>
