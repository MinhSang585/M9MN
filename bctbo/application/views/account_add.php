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
							<?php echo form_open('account/submit', array('id' => 'account-form', 'name' => 'account-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="" maxlength="16">
										</div>
									</div>
									<div class="form-group row">
										<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="nickname" name="nickname" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="password" class="col-5 col-form-label"><?php echo $this->lang->line('label_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="password" name="password" maxlength="15">
										</div>
									</div>
									<div class="form-group row">
										<label for="passconf" class="col-5 col-form-label"><?php echo $this->lang->line('label_confirm_password');?></label>
										<div class="col-7">
											<input type="password" class="form-control" id="passconf" name="passconf" maxlength="15">
										</div>
									</div>
									<div class="form-group row">
										<label for="white_list_ip" class="col-5 col-form-label"><?php echo $this->lang->line('label_white_list_ip');?></label>
										<div class="col-7">
											<select class="select2 col-12 white_list_ip" id="white_list_ip" name="white_list_ip[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="user_role" class="col-5 col-form-label"><?php echo $this->lang->line('label_user_role');?></label>
										<div class="col-7">
											<select class="form-control select2bs4" id="user_role" name="user_role">
												<option value=""><?php echo $this->lang->line('label_select');?></option>
												<?php
													if(!empty($role_list)){
														foreach ($role_list as $role_list_row){
															if($this->session->userdata('user_type') != 1) { #AGENT
																	if($this->session->userdata('user_type') == 2) { #Shareholder
																		if($role_list_row['user_role_id'] == 11) { #SHAREHOLDER ROLE	
																			echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
																		}
																	}
																	else if($this->session->userdata('user_type') == 3) { #Agent Head
																		if($role_list_row['user_role_id'] == 12) { #AGENT HEAD ROLE	
																			echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
																		}
																	}
																	else {
																		if($role_list_row['user_role_id'] == 10) { #AGENT ROLE	
																			echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
																		}	
																	}																	
																}
																else { #ADMIN	
																	echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
																}															
														}
													}
												?>
											</select>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="upline" name="upline" value="<?php echo (isset($username) ? $username : '');?>">
									<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
								</div>
								<!-- /.card-footer -->
							<?php echo form_close();?>
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
			$('.select2.white_list_ip').select2({
				tags: true,
				casesensitive: false,
			});
			var is_allowed = true;
			var form = $('#account-form');
			
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
									parent.$('#account-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.password_error != '') {
										message = json.msg.password_error;
									}
									else if(json.msg.passconf_error != '') {
										message = json.msg.passconf_error;
									}
									else if(json.msg.white_list_ip_error != '') {
										message = json.msg.white_list_ip_error;
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
					username: {
						required: true,
						rangelength: [6, 16]
					},
					password: {
						required: true,
						rangelength: [6, 15]
					},
					passconf: {
						required: true
					}
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_username');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_username');?>",
					},
					password: {
						required: "<?php echo $this->lang->line('error_enter_password');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_password');?>",
					},
					passconf: {
						required: "<?php echo $this->lang->line('error_enter_confirm_password');?>",
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
