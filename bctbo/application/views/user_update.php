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
							<?php echo form_open('user/update', array('id' => 'user-form', 'name' => 'user-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_upline');?></label>
										<div class="col-7">
											<?php if($current_user_type != 1){ ?>
											<label class="col-form-label font-weight-normal"><?php echo (isset($upline) ? $upline : '');?></label>
											<?php } else { ?>
											<select class="form-control select2bs4" id="upline_select" name="upline_select">
												<?php foreach($uplineList as $uplineItem){ ?>
												<option value="<?=$uplineItem['user_id']?>" <?php if($uplineItem['username'] == $upline){ ?> selected <?php } ?>><?=$uplineItem['username']?></option>
												<?php } ?>	
											</select>	
											<?php } ?>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo (isset($nickname) ? $nickname : '');?>" maxlength="32">
										</div>
									</div>
									<div class="form-group row">
										<label for="mobile" class="col-5 col-form-label"><?php echo $this->lang->line('label_mobile');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="mobile" name="mobile" value="<?php echo (isset($mobile) ? $mobile : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-5 col-form-label"><?php echo $this->lang->line('label_email');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="email" name="email" value="<?php echo (isset($email) ? $email : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
															if(isset($user_role) && $user_role == $role_list_row['user_role_id']){
																echo '<option value="' . $role_list_row['user_role_id'] . '" selected>' . $role_list_row['role_name'] . '</option>';
															}
															else{
																#echo '<option value="' . $role_list_row['user_role_id'] . '">' . $role_list_row['role_name'] . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="white_list_ip" class="col-5 col-form-label"><?php echo $this->lang->line('label_white_list_ip');?></label>
										<div class="col-7">
											<select class="select2 col-12 white_list_ip" id="white_list_ip" name="white_list_ip[]" multiple="multiple" data-placeholder="<?php echo $this->lang->line('label_select');?>">
												<?php 
													if(isset($white_list_ip)){
														$white_list_ip_data = array_filter(explode(',', $white_list_ip));
														if(sizeof($white_list_ip_data)>0){
															foreach($white_list_ip_data as $white_list_ip_data_row){
																echo "<option value='".$white_list_ip_data_row."' selected>".$white_list_ip_data_row."</option>";
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="possess" class="col-5 col-form-label"><?php echo $this->lang->line('label_possess');?></label>
										<div class="col-3">
											<select class="select2 col-12 white_list_ip" id="possess" name="possess" data-placeholder="<?php echo $this->lang->line('label_select'); ?>">
												<?php
												foreach ($arrayPossess as $poss) {
													if ($poss == $possess) { ?>
														<option value="<?= $poss ?>" selected><?= $poss ?></option>
													<?php } else { ?>
														<option value="<?= $poss ?>"><?= $poss ?></option>
												<?php }
												} ?>
											</select>
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['possess']) ? $upline_data['possess'] : '0');?>%</label>
									</div>
									<div class="form-group row">
										<label for="sport_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_sport_comm');?></label>
										<div class="col-3">
											<input type="text" class="form-control" id="sport_comm" name="sport_comm" value="<?php echo (isset($sport_comm) ? $sport_comm : '0.0');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['sport_comm']) ? $upline_data['sport_comm'] : '0.0');?>%</label>
									</div>
									<div class="form-group row">
										<label for="casino_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_casino_comm');?></label>
										<div class="col-3">
											<input type="text" class="form-control" id="casino_comm" name="casino_comm" value="<?php echo (isset($casino_comm) ? $casino_comm : '0.0');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['casino_comm']) ? $upline_data['casino_comm'] : '0.0');?>%</label>
									</div>
									<div class="form-group row">
										<label for="slots_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_slots_comm');?></label>
										<div class="col-3">
											<input type="text" class="form-control" id="slots_comm" name="slots_comm" value="<?php echo (isset($slots_comm) ? $slots_comm : '0.0');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['slots_comm']) ? $upline_data['slots_comm'] : '0.0');?>%</label>
									</div>
									<div class="form-group row">
										<label for="cf_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_cf_comm');?></label>
										<div class="col-3">
											<input type="text" class="form-control" id="cf_comm" name="cf_comm" value="<?php echo (isset($cf_comm) ? $cf_comm : '0.0');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['cf_comm']) ? $upline_data['cf_comm'] : '0.0');?>%</label>
									</div>
									<div class="form-group row">
										<label for="other_comm" class="col-5 col-form-label"><?php echo $this->lang->line('label_other_comm');?></label>
										<div class="col-3">
											<input type="text" class="form-control" id="other_comm" name="other_comm" value="<?php echo (isset($other_comm) ? $other_comm : '0.0');?>">
										</div>
										<label class="col-4 col-form-label font-weight-normal">/ &nbsp; <?php echo (isset($upline_data['other_comm']) ? $upline_data['other_comm'] : '0.0');?>%</label>
									</div>
									<div class="form-group row">
										<?php echo $this->lang->line('notice_change_possess_and_commission');?>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="user_id" name="user_id" value="<?php echo (isset($user_id) ? $user_id : '');?>">
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
			var form = $('#user-form');
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
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc3_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc3_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									parent.$('#user-table-' + json.response.user_type).DataTable().ajax.reload();
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
									else if(json.msg.possess_error != '') {
										message = json.msg.possess_error;
									}
									else if(json.msg.sport_comm_error != '') {
										message = json.msg.sport_comm_error;
									}
									else if(json.msg.casino_comm_error != '') {
										message = json.msg.casino_comm_error;
									}
									else if(json.msg.slots_comm_error != '') {
										message = json.msg.slots_comm_error;
									}
									else if(json.msg.cf_comm_error != '') {
										message = json.msg.cf_comm_error;
									}
									else if(json.msg.other_comm_error != '') {
										message = json.msg.other_comm_error;
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
					nickname: {
						required: true
					},
					mobile: {
						digits: true
					},
					email: {
						email: true
					},
					possess: {
						required: true,
						number: true
					},
					sport_comm: {
						required: true,
						number: true
					},
					casino_comm: {
						required: true,
						number: true
					},
					slots_comm: {
						required: true,
						number: true
					},
					cf_comm: {
						required: true,
						number: true
					},
					other_comm: {
						required: true,
						number: true
					}
				},
				messages: {
					nickname: {
						required: "<?php echo $this->lang->line('error_enter_nickname');?>",
					},
					mobile: {
						digits: "<?php echo $this->lang->line('error_invalid_mobile');?>",
					},
					email: {
						email: "<?php echo $this->lang->line('error_invalid_email');?>",
					},
					possess: {
						required: "<?php echo $this->lang->line('error_invalid_possess');?>",
						number: "<?php echo $this->lang->line('error_invalid_possess');?>",
					},
					sport_comm: {
						required: "<?php echo $this->lang->line('error_invalid_sport_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_sport_comm');?>",
					},
					casino_comm: {
						required: "<?php echo $this->lang->line('error_invalid_casino_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_casino_comm');?>",
					},
					slots_comm: {
						required: "<?php echo $this->lang->line('error_invalid_slots_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_slots_comm');?>",
					},
					cf_comm: {
						required: "<?php echo $this->lang->line('error_invalid_cf_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_cf_comm');?>",
					},
					other_comm: {
						required: "<?php echo $this->lang->line('error_invalid_other_comm');?>",
						number: "<?php echo $this->lang->line('error_invalid_other_comm');?>",
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