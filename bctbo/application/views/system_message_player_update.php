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
							<?php echo form_open('message/player_update', array('id' => 'message_player-form', 'name' => 'message_player-form', 'class' => 'form-horizontal'));?>
							<div class="card-body">
								<div class="form-group row">
									<label for="system_message_title" class="col-5 col-form-label"><?php echo $this->lang->line('label_message_name');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($message_data['system_message_name']) ? $message_data['system_message_name'] : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($username) ? $username : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="is_read" class="col-5 col-form-label"><?php echo $this->lang->line('label_read');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (isset($is_read) ? $this->lang->line(get_message_read_status($is_read)) : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
									<div class="col-7">
										<input type="checkbox" id="active" name="active" value="1" <?php echo ((isset($active) && $active == STATUS_ACTIVE) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
									</div>
								</div>
								<div class="form-group row">
									<label for="created_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($created_by)) ? $created_by : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="created_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (($created_date > 0) ? date('Y-m-d H:i:s', $created_date) : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="updated_by" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_by');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (( ! empty($updated_by)) ? $updated_by : '-');?></label>
									</div>
								</div>
								<div class="form-group row">
									<label for="updated_date" class="col-5 col-form-label"><?php echo $this->lang->line('label_created_date');?></label>
									<div class="col-7">
										<label class="col-form-label font-weight-normal"><?php echo (($updated_date > 0) ? date('Y-m-d H:i:s', $updated_date) : '-');?></label>
									</div>
								</div>
							</div>

							<!-- /.card-body -->
							<div class="card-footer">
								<input type="hidden" id="system_message_user_id" name="system_message_user_id" value="<?php echo (isset($system_message_user_id) ? $system_message_user_id : '');?>">
								<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
								<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
							</div>
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
			var is_allowed = true;
			var form = $('#message_player-form');

			$('.select2').select2();

			$(document).on('keypress', '.select2', function () {
			    $(this).val($(this).val().replace(/[^\d].+/, ""));
			    if ((event.which < 48 || event.which > 57)) {
			      event.preventDefault();
			    }
			});
			
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
								console.log(json);
								parent.$('meta[name=csrf_token]').attr('content', json.csrfHash);
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>') {
									message = json.msg;
									msg_icon = 1;
									parent.$('#uc2_' + json.response.id).html(json.response.active);
									parent.$('#uc5_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc6_' + json.response.id).html(json.response.updated_date);
									
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc2_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc2_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}
									parent.layer.close(index);
								}
								else {
									if(json.msg.system_message_user_id_error != ''){
										message = json.msg.system_message_user_id_error;
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
					system_message_user_id: {
						required: true
					},
				},
				messages: {
					system_message_user_id: {
						required: "<?php echo $this->lang->line('error_enter_message_title');?>",
					},
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
