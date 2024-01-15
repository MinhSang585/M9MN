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
							<?php echo form_open_multipart('withdrawal/fee_setting_submit', array('id' => 'withdrawal_fee_setting-form', 'name' => 'withdrawal_fee_setting-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="withdrawal_times" class="col-5 col-form-label"><?php echo $this->lang->line('label_withdrawal_times');?></label>
										<div class="col-7">
											<input type="number" class="form-control col-7" id="withdrawal_times" name="withdrawal_times" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="withdrawal_min" class="col-5 col-form-label"><?php echo $this->lang->line('label_min_request_amount');?></label>
										<div class="col-7">
											<input type="number" class="form-control col-12" id="withdrawal_min" name="withdrawal_min" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="withdrawal_max" class="col-5 col-form-label"><?php echo $this->lang->line('label_max_request_amount');?></label>
										<div class="col-7">
											<input type="number" class="form-control col-12" id="withdrawal_max" name="withdrawal_max" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="withdrawal_rate_type" class="col-5 col-form-label"><?php echo $this->lang->line('label_rate_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="withdrawal_rate_type" name="withdrawal_rate_type">
												<?php
													foreach(get_withdrawal_rate_type() as $k => $v)
													{
														echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="withdrawal_rate_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_amount_rate');?></label>
										<div class="col-7">
											<input type="number" class="form-control col-7" id="withdrawal_rate_amount" name="withdrawal_rate_amount" value="">
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
			var form = $('#withdrawal_fee_setting-form');
			
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
									parent.$('#withdrawal_fee_rate-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.withdrawal_times_error != '') {
										message = json.msg.withdrawal_times_error;
									}
									else if(json.msg.withdrawal_min_error != '') {
										message = json.msg.withdrawal_min_error;
									}
									else if(json.msg.withdrawal_max_error != '') {
										message = json.msg.withdrawal_max_error;
									}
									else if(json.msg.withdrawal_rate_type_error != '') {
										message = json.msg.withdrawal_rate_type_error;
									}
									else if(json.msg.withdrawal_rate_amount_error != '') {
										message = json.msg.withdrawal_rate_amount_error;
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
					withdrawal_times: {
						required: true,
						digits: true,
					},
					withdrawal_min: {
						required: true,
					},
					withdrawal_max: {
						required: true,
					},
					withdrawal_rate_type: {
						required: true,
						digits: true
					},
					withdrawal_rate_amount: {
						required: true,
					}
				},
				messages: {
					withdrawal_times: {
						required: "<?php echo $this->lang->line('error_invalid_withdrawal_times');?>",
						digits: "<?php echo $this->lang->line('error_invalid_withdrawal_times');?>",
					},
					withdrawal_min: {
						required: "<?php echo $this->lang->line('error_invalid_withdrawal_min');?>",
						digits: "<?php echo $this->lang->line('error_invalid_withdrawal_min');?>",
					},
					withdrawal_max: {
						required: "<?php echo $this->lang->line('error_invalid_withdrawal_max');?>",
						digits: "<?php echo $this->lang->line('error_invalid_withdrawal_max');?>",
					},
					withdrawal_rate_type: {
						required: "<?php echo $this->lang->line('error_invalid_withdrawal_rate_type');?>",
						digits: "<?php echo $this->lang->line('error_invalid_withdrawal_rate_type');?>",
					},
					withdrawal_rate_amount: {
						required: "<?php echo $this->lang->line('error_invalid_withdrawal_rate_amount');?>",
						digits: "<?php echo $this->lang->line('error_invalid_withdrawal_rate_amount');?>",
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
