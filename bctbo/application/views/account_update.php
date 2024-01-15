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
							<?php echo form_open('account/update', array('id' => 'account-form', 'name' => 'account-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="nickname" class="col-5 col-form-label"><?php echo $this->lang->line('label_nickname');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo (isset($nickname) ? $nickname : '');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
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
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="sub_account_id" name="sub_account_id" value="<?php echo (isset($sub_account_id) ? $sub_account_id : '');?>">
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
								var message = json.msg;
								var msg_icon = 2;
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									msg_icon = 1;
									parent.$('#uc1_' + json.response.id).html(json.response.nickname);
									parent.$('#uc2_' + json.response.id).html(json.response.active);
									parent.$('#uc3_' + json.response.id).html(json.response.role_name);
									parent.$('#uc101_' + json.response.id).html(json.response.white_list_ip);
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc2_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc2_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									parent.layer.close(index);
								}
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});
			form.validate();
		});
	</script>
</body>
</html>