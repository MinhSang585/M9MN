
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
							<?php echo form_open('player/update_detail_version_two', array('id' => 'player-form', 'name' => 'player-form', 'class' => 'form-horizontal'));?>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_NICKNAME) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($nickname) ? $nickname : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_LINE_ID) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="line_id" class="col-5 col-form-label"><?php echo $this->lang->line('im_line');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($line_id) ? $line_id : '');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_MOBILE) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_mobile');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($mobile) && ! empty($mobile)) ? $mobile : '-');?></label>
									</div>
								</div>
								<div class="form-group row" <?php if(permission_validation(PERMISSION_PLAYER_EMAIL) == TRUE){}else{echo 'style="display:none"';}?>>
									<label for="email" class="col-5 col-form-label"><?php echo $this->lang->line('label_email');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($email) && ! empty($email)) ? $email : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_referrer');?>:</label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo ((isset($referrer) && ! empty($referrer)) ? $referrer : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-5 col-form-label"><?php echo $this->lang->line('label_bank_group');?>:</label>
									<?php
										$bank_show_name = "";
										for($i=0;$i<sizeof($bank_group_list);$i++)
										{
											if(isset($bank_group_list)) 
											{
												$arr = explode(',', $bank_group_id);
												$arr = array_values(array_filter($arr));
												if(in_array($bank_group_list[$i]['group_id'], $arr)) 
												{
													if(empty($bank_show_name)){
														$bank_show_name .= $bank_group_list[$i]['group_name'];
													}else{
														$bank_show_name .= ", ".$bank_group_list[$i]['group_name'];
													}
												}
											}
										}
									?>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo $bank_show_name;?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
									<div class="col-7">
										<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<label for="is_offline_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_offline_banking');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal <?php echo ((isset($is_offline_deposit) && $is_offline_deposit == STATUS_ACTIVE) ? "text-success" : "text-pending");?>"><?php echo ((isset($is_offline_deposit) && $is_offline_deposit == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="is_online_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_online_banking');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal <?php echo ((isset($is_online_deposit) && $is_online_deposit == STATUS_ACTIVE) ? "text-success" : "text-pending");?>"><?php echo ((isset($is_online_deposit) && $is_online_deposit == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="is_credit_card_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_credit_card');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal <?php echo ((isset($is_credit_card_deposit) && $is_credit_card_deposit == STATUS_ACTIVE) ? "text-success" : "text-pending");?>"><?php echo ((isset($is_credit_card_deposit) && $is_credit_card_deposit == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="is_hypermart_deposit" class="col-5 col-form-label"><?php echo $this->lang->line('deposit_hypermart');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal <?php echo ((isset($is_hypermart_deposit) && $is_hypermart_deposit == STATUS_ACTIVE) ? "text-success" : "text-pending");?>"><?php echo ((isset($is_hypermart_deposit) && $is_hypermart_deposit == STATUS_ACTIVE) ? $this->lang->line('status_active') : $this->lang->line('status_inactive'));?></label>
									</div>
								</div>
							</div>
							<!-- /.card-body -->
							<div class="card-footer">
								<input type="hidden" id="player_id" name="player_id" value="<?php echo (isset($player_id) ? $player_id : '');?>">
								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_close');?></button>
							</div>
							<?php echo form_close();?>
							<!-- /.card-footer -->
						</div>
						<!-- /.card -->
					</div>
					<!--/.col (left) -->
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
			var is_allowed = true;
			var form = $('#player-form');
			$('.select2').select2();

			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			
			var index = parent.layer.getFrameIndex(window.name);
			
			$('#button-cancel').click(function() {
				parent.layer.close(index);
			});


			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						$.ajax({url: form.attr('action'),
							data: form.serialize(),
							type: 'post',                  
							async: 'true',
							beforeSend: function() {
								layer.load(1);
							},
							complete: function() {
								layer.closeAll('loading');
								is_allowed = true;
							},
							success: function (data) {
								var json = JSON.parse(JSON.stringify(data));
								var message = '';
								var msg_icon = 2;
								
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#uc1_' + json.response.id).html(json.response.nickname);
									parent.$('#uc3_' + json.response.id).html(json.response.active);
									parent.$('#uc4_' + json.response.id).html(json.response.player_type);
									
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc3_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc3_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									
									parent.layer.close(index);
								}
								else {
									if(json.msg.nickname_error != '') {
										message = json.msg.nickname_error;
									}
									else if(json.msg.mobile_error != '') {
										message = json.msg.mobile_error;
									}
									else if(json.msg.email_error != '') {
										message = json.msg.email_error;
									}
									else if(json.msg.player_type_error != ''){
										message = json.msg.player_type_error;
									}
									else if(json.msg.win_loss_suspend_limit_error != '') {
										message = json.msg.win_loss_suspend_limit_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					nickname: {
						//required: true
					},
					mobile: {
						digits: true
					},
					email: {
						email: true
					},
					player_type:{
						required: true	
					},
					win_loss_suspend_limit: {
						required: true,
						digits: true
					}
				},
				messages: {
					nickname: {
						//required: "<?php echo $this->lang->line('error_enter_nickname');?>",
					},
					mobile: {
						digits: "<?php echo $this->lang->line('error_invalid_mobile');?>",
					},
					email: {
						email: "<?php echo $this->lang->line('error_invalid_email');?>",
					},
					player_type:{
						required: "<?php echo $this->lang->line('error_select_player_type');?>",
					},
					win_loss_suspend_limit: {
						required: "<?php echo $this->lang->line('error_enter_win_loss_suspend_limit');?>",
						digits: "<?php echo str_replace('%s', strtolower($this->lang->line('label_win_loss_suspend_limit')), $this->lang->line('error_only_digits_allowed'));?>",
					}
				},
				errorElement: 'span',
				errorPlacement: function (error, element) {
					error.addClass('invalid-feedback');
					element.closest('.form-group').append(error);
				},
				highlight: function (element, errorClass, validClass) {
					$(element).addClass('is-invalid');
				},
				unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass('is-invalid');
				}
			});
		});	
	</script>
</body>
</html>
