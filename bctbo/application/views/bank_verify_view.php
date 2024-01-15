<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="<?php echo get_language_code('iso');?>">
<head>
	<?php $this->load->view('parts/head_meta');?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
	<div class="wrapper">
		<!-- Navbar -->
		<?php $this->load->view('parts/navbar_page');?>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<?php $this->load->view('parts/sidebar_page');?>
		<!-- /.sidebar -->
	  
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<?php $this->load->view('parts/header_page');?>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<div class="row">
						<!-- left column -->
						<div class="col-md-12">
							<!-- jquery validation -->
							<div class="card card-primary">
								<div class="card-header">
									<h3 class="card-title"><?php echo $this->lang->line('title_bank_withdrawal_verify');?></h3>
								</div>
								<!-- /.card-header -->
								<!-- form start -->
								<?php echo form_open_multipart('bank/verify_submit', array('id' => 'bank_verify_submit-form', 'name' => 'bank_verify_submit-form', 'class' => 'form-horizontal'));?>
									<div class="card-body">
										<div class="form-group row">
											<label for="payment_gateway_code" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_name');?></label>
											<div class="col-sm-8">
												<select class="form-control select2bs4 col-7" id="payment_gateway_code" name="payment_gateway_code">
													<?php
														foreach(get_payment_gateway_code_bank_withdrawal_verify() as $k => $v)
														{
															echo '<option value="' . $k . '" '.$select.'>' . $this->lang->line($v) . '</option>';
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label for="online_deposit_sound" class="col-sm-4 col-form-label"><?php echo $this->lang->line('label_bank_withdrawal_verify');?></label>
											<div class="col-sm-8">
												<div class="custom-file col-sm-10">
													<input type="file" class="custom-file-input" id="bank_withdrawal_verify" name="bank_withdrawal_verify">
													<label class="custom-file-label" for="bank_withdrawal_verify"><?php echo $this->lang->line('button_choose_file');?></label>
												</div>
												<p class="text-sm mb-0" id="bank_withdrawal_verify"></p>
											</div>
										</div>
									</div>
									<!-- /.card-body -->
									<div class="card-footer">
										<button type="submit" class="btn btn-primary"><?php echo $this->lang->line('button_submit');?></button>
									</div>
									<!-- /.card-footer -->
								<?php echo form_close();?>
								<?php echo form_open('bank/verify_export', 'class="export" id="export_form"');?>
								<input type="hidden" name="payment_gateway_code" id="payment_gateway_code_export">
								<textarea style="display: none;" class="form-control" id="bank_withdrawal_verify_export" name="bank_withdrawal_verify_export" rows="10"></textarea>
								<?php echo form_close(); ?>
							</div>
							<!-- /.card -->
						</div>
						<!--/.col (left) -->
						<!-- right column -->
						<div class="col-md-6">

						</div>
						<!--/.col (right) -->
					</div>
					<!-- /.row -->
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Main Footer -->
		<?php $this->load->view('parts/footer_page');?>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->
	<?php $this->load->view('parts/footer_js');?>

	<script type="text/javascript">
		$(document).ready(function() {

			var is_allowed = true;
			var form = $('#bank_verify_submit-form');
			$("input[data-bootstrap-switch]").each(function(){
				$(this).bootstrapSwitch('state', $(this).prop('checked'));
			});
			bsCustomFileInput.init();
			
			$.validator.setDefaults({
				submitHandler: function () {
					if(is_allowed == true) {
						is_allowed = false;
						
						var file_form = form[0];
						var formData = new FormData(file_form);
						$.each($("input[type='file']")[0].files, function(i, file) {
							formData.append('file', file);
						});
						
						$.ajax({url: form.attr('action'),
							data: formData,
							type: 'post',	
							processData: false,
							contentType: false,								
							async: 'true',
							beforeSend: function() {
								layer.load(1);
							},
							complete: function() {
								is_allowed = true;
							},
							success: function (data) {
								var json = JSON.parse(JSON.stringify(data));
								var message = '';
								var msg_icon = 2;
								
								if(json.status == '<?php echo EXIT_SUCCESS;?>'){
									message = json.msg;
									msg_icon = 1;
									var payment_gateway_code_export = $('#payment_gateway_code').val();
									$('#payment_gateway_code_export').val(payment_gateway_code_export);
									$('#bank_withdrawal_verify_export').html(json.result);
									setTimeout(function(){
										layer.closeAll('loading');
										var form_excel = $('#export_form').submit();
									}, 5000);
								}
								else {
									layer.closeAll('loading');
									if(json.msg.general_error != '') {
										message = json.msg.general_error;
									}

									layer.alert(message, {icon: msg_icon, title: '<?php echo $this->lang->line('label_info');?>', btn: '<?php echo $this->lang->line('button_close');?>'});
								}
								
								$("input[name='" + json.csrfTokenName + "']").val(json.csrfHash);
							},
							error: function (request,error) {
							}
						});  
					}
				}
			});

			form.validate({
				rules: {
					payment_gateway_code: {
						required: true,
					},
				},
				messages: {
					payment_gateway_code: {
						required: "<?php echo $this->lang->line('error_select_payment_gateway_name');?>",
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