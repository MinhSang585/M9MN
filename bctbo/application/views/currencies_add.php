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
							<?php echo form_open_multipart('currencies/submit', array('id' => 'currencies-form', 'name' => 'currencies-form', 'class' => 'form-horizontal'));?>
								<div class="card-body">


									<div class="form-group row">
										<label for="currency_name" class="col-5 col-form-label"><?php echo $this->lang->line('label_currency_name');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="currency_name" name="currency_name" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_code" class="col-5 col-form-label"><?php echo $this->lang->line('label_currency_code');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="currency_code" name="currency_code" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="currency_symbol" class="col-5 col-form-label"><?php echo $this->lang->line('label_currency_symbol');?></label>
										<div class="col-7">
											<input type="text" class="form-control" id="currency_symbol" name="currency_symbol" value="">
										</div>
									</div>
									<div class="form-group row">
										<label for="t_currency_rate" class="col-5 col-form-label"><?php echo $this->lang->line('label_transfer_rate');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="t_currency_rate" name="t_currency_rate" value="1.0000" step=".00001">
										</div>
									</div>
									<div class="form-group row">
										<label for="t_fee" class="col-5 col-form-label"><?php echo $this->lang->line('label_transfer_fee');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="t_fee" name="t_fee" value="0.00" step=".01">
										</div>
									</div>
									<div class="form-group row">
										<label for="d_currency_rate" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_rate');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="d_currency_rate" name="d_currency_rate" value="1.0000"  step=".00001">
										</div>
									</div>
									<div class="form-group row">
										<label for="d_fee" class="col-5 col-form-label"><?php echo $this->lang->line('label_deposit_fee');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="d_fee" name="d_fee" value="0.00" step=".01">
										</div>
									</div>
									<div class="form-group row">
										<label for="w_currency_rate" class="col-5 col-form-label"><?php echo $this->lang->line('label_withdrawal_rate');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="w_currency_rate" name="w_currency_rate" value="1.0000" step=".00001">
										</div>
									</div>
									<div class="form-group row">
										<label for="w_fee" class="col-5 col-form-label"><?php echo $this->lang->line('label_withdrawal_fee');?></label>
										<div class="col-3">
											<input type="number" class="form-control" id="w_fee" name="w_fee" value="0.00" step=".01">
										</div>
									</div>

									<div class="form-group row">
										<label for="active" class="col-5 col-form-label"><?php echo $this->lang->line('label_status');?></label>
										<div class="col-7">
											<input type="checkbox" id="active" name="active" value="1" checked data-bootstrap-switch data-off-color="secondary" data-on-color="success">
										</div>
									</div>
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
										<button type="button" id="button-cancel" class="btn btn-default ml-2"><?php echo $this->lang->line('button_cancel');?></button>
									</div>
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
			var form = $('#currencies-form');

			
			
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
									parent.$('#currencies-table').DataTable().ajax.reload();
									parent.layer.close(index);
								}
								else {
									if(json.msg.currency_name_error != ''){
										message = json.msg.currency_name_error;
									}
									else if(json.msg.currency_code_error != '') {
										message = json.msg.currency_code_error;
									}
									else if(json.msg.currency_symbol_error != '') {
										message = json.msg.currency_symbol_error;
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
					currency_name: {
						required: true,
						rangelength: [1, 32]
					},
					currency_code: {
						required: true,
						rangelength: [1, 4]
					},
					currency_symbol: {
						required: true,
						rangelength: [1, 4]
					},
					t_currency_rate: {
						number: true
					},
					d_currency_rate: {
						number: true
					},
					w_currency_rate: {
						number: true
					},
					t_fee: {
						number: true
					},
					d_fee: {
						number: true
					},
					w_fee: {
						number: true
					},
				},
				messages: {
					currency_name: {
						required: "<?php echo $this->lang->line('error_enter_currency_name');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_currency_name');?>",
					},
					currency_code: {
						required: "<?php echo $this->lang->line('error_enter_currency_code');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_currency_code');?>",
					},
					currency_symbol: {
						required: "<?php echo $this->lang->line('error_enter_currency_symbol');?>",
						rangelength: "<?php echo $this->lang->line('error_invalid_currency_symbol');?>",
					},
					t_currency_rate: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					d_currency_rate: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					w_currency_rate: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					t_fee: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					d_fee: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
					},
					w_fee: {
						number: "<?php echo $this->lang->line('error_invalid_points');?>",
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
