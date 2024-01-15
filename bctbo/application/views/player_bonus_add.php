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
							<?php echo form_open_multipart('bonus/player_submit', array('id' => 'bonus_player_submit-form', 'name' => 'bonus_player_submit-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_player_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="promotion_content" class="col-5 col-form-label"><?php echo $this->lang->line('label_bonus_name');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="bonus_id" name="bonus_id">
												<?php
													if(isset($bonus)){
														if(sizeof($bonus)>0){
															foreach($bonus as $row){
																echo '<option value="' . $row['bonus_id'] . '">' . $row['bonus_name'] . '</option>';
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="reward_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_reward_amount');?></label>
										<div class="col-7">
											<input type="number" class="form-control" id="reward_amount" name="reward_amount" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="achieve_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_total_rollover');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="achieve_amount" name="achieve_amount" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="remark" class="col-5 col-form-label"><?php echo $this->lang->line('label_remark');?></label>
										<div class="col-7">
											<textarea class="form-control" id="remark" name="remark" rows="3"></textarea>
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
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
			var is_allowed = true;
			var form = $('#bonus_player_submit-form');
			
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
									parent.$('#player_bonus-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.username_error != '') {
										message = json.msg.username_error;
									}
									else if(json.msg.bonus_id_error != '') {
										message = json.msg.bonus_id_error;
									}
									else if(json.msg.reward_amount_error != '') {
										message = json.msg.reward_amount_error;
									}
									else if(json.msg.achieve_amount_error != '') {
										message = json.msg.achieve_amount_error;
									}
									else if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}
								}
								
								parent.layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});

							},
							error: function (request,error){
							}
						});  
					}
				}
			});
			
			form.validate({
				rules: {
					username: {
						required: true
					},
					bonus_id: {
						required: true,
					},
					reward_amount : {
						required: true,
						number: true
					},
					achieve_amount: {
						required: true,
						number: true
					},
				},
				messages: {
					username: {
						required: "<?php echo $this->lang->line('error_enter_player_username');?>",
					},
					bonus_id: {
						required: "<?php echo $this->lang->line('error_enter_bonus_name');?>",
					},
					reward_amount : {
						required: "<?php echo $this->lang->line('error_invalid_amount');?>",
						number: "<?php echo $this->lang->line('error_invalid_amount');?>",
					},
					achieve_amount: {
						required: "<?php echo $this->lang->line('error_invalid_amount');?>",
						number: "<?php echo $this->lang->line('error_invalid_amount');?>",
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
