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
							<?php echo form_open('paymentgateway/player_amount_update', array('id' => 'paymentgateway-form', 'name' => 'paymentgateway-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">
									<div class="form-group row">
										<label for="payment_gateway_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_name');?></label>
										<div class="col-7">
											<label class="col-form-label font-weight-normal"><?php echo (isset($payment_gateway_code) ? $this->lang->line(get_payment_gateway_code($payment_gateway_code)) : '');?></label>
										</div>
									</div>
									<div class="form-group row">
										<label for="payment_gateway_type_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_type');?></label>
										<div class="col-7">
											<select class="form-control select2bs4 col-7" id="payment_gateway_type_code" name="payment_gateway_type_code">
												<option value="0"><?php echo $this->lang->line('label_all');?></option>
												<?php
													$get_deposit_type = get_deposit_type();
													if(isset($get_deposit_type) && sizeof($get_deposit_type)>0){
														foreach($get_deposit_type as $k => $v)
														{
															if($k != DEPOSIT_OFFLINE_BANKING){
																if(isset($payment_gateway_type_code) && $payment_gateway_type_code == $k){
																	echo '<option value="' . $k . '" selected="selected">' . $this->lang->line($v) . '</option>';
																}else{
																	echo '<option value="' . $k . '">' . $this->lang->line($v) . '</option>';
																}
															}
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group row">
										<label for="username" class="col-5 col-form-label"><?php echo $this->lang->line('label_username');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="username" name="username" value="<?php echo (isset($username) ? $username : '');?>" maxlength="16">
										</div>
									</div>
									<div class="form-group row">
										<label for="min_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_min_amounts');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-6" id="min_amount" name="min_amount" value="<?php echo (isset($min_amount) ? $min_amount : '0.00');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="max_amount" class="col-5 col-form-label"><?php echo $this->lang->line('label_max_amounts');?></label>
										<div class="col-7">
											<input type="text" class="form-control col-6" id="max_amount" name="max_amount" value="<?php echo (isset($max_amount) ? $max_amount : '0.00');?>">
										</div>
									</div>
									<div class="form-group row">
										<label for="fixed_maintenance" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1"  <?php echo ((isset($active) && $active == STATUS_YES) ? 'checked' : '');?> data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
								</div>
								<!-- /.card-body -->
								<div class="card-footer">
									<input type="hidden" id="payment_gateway_player_amount_id" name="payment_gateway_player_amount_id" value="<?php echo (isset($payment_gateway_player_amount_id) ? $payment_gateway_player_amount_id : '');?>">
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
			var form = $('#paymentgateway-form');
			
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
									parent.$('#uc2_' + json.response.id).html(json.response.active);
									parent.$('#uc3_' + json.response.id).html(json.response.payment_gateway_type_name);


									parent.$('#uc4_' + json.response.id).html(json.response.username);
									parent.$('#uc5_' + json.response.id).html(json.response.min_amount);
									parent.$('#uc6_' + json.response.id).html(json.response.max_amount);


									parent.$('#uc7_' + json.response.id).html(json.response.updated_by);
									parent.$('#uc8_' + json.response.id).html(json.response.updated_date);
									
									
									if(json.response.active_code == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('#uc2_' + json.response.id).removeClass('bg-secondary').addClass('bg-success');
									}
									else {
										parent.$('#uc2_' + json.response.id).removeClass('bg-success').addClass('bg-secondary');
									}

									if(json.response.is_reset_daily_limit_same_payment_gateway == '<?php echo STATUS_ACTIVE;?>') {
										parent.$('.' + json.response.payment_gateway_code).html(json.response.payment_gateway_current_usage);
									}
									parent.layer.close(index);
								}
								else {
									if(json.msg.payment_gateway_sequence_error != '') {
										message = json.msg.payment_gateway_sequence_error;
									}
									else if(json.msg.payment_gateway_daily_limit_error != '') {
										message = json.msg.payment_gateway_daily_limit_error;
									}
									else if(json.msg.payment_gateway_code_error != '') {
										message = json.msg.payment_gateway_code_error;
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
					min_amount: {
						required: true,
					},
					max_amount: {
						required: true,
					},
				},
				messages: {
					min_amount: {
						required: "<?php echo $this->lang->line('error_enter_min_request_amount');?>",
					},
					max_amount: {
						required: "<?php echo $this->lang->line('error_enter_max_request_amount');?>",
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
